<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GarantiaIndividual;
use App\Models\Alquiler;

class ProcesarGarantiasVencidas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vestimenta:procesar-garantias-vencidas {--days=30 : Días después de la fecha de devolución para considerar vencida} {--dry-run : Solo mostrar qué se procesaría}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesa automáticamente las garantías individuales de alquileres vencidos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $diasVencimiento = $this->option('days');
        $fechaLimite = now()->subDays($diasVencimiento);

        $this->info("🔄 Procesando garantías vencidas (más de {$diasVencimiento} días)...");

        // Buscar alquileres vencidos con garantías activas
        $alquileresVencidos = Alquiler::where('estado', 'ACTIVO')
                                    ->where('fecha_devolucion_programada', '<', $fechaLimite)
                                    ->whereHas('garantiasIndividuales', function($query) {
                                        $query->where('estado_garantia', 'ACTIVA');
                                    })
                                    ->with(['garantiasIndividuales', 'cliente'])
                                    ->get();

        if ($alquileresVencidos->isEmpty()) {
            $this->info('✅ No hay garantías vencidas para procesar.');
            return 0;
        }

        $this->info("📋 Encontrados {$alquileresVencidos->count()} alquileres con garantías vencidas:");

        $totalGarantiasRetenidas = 0;
        $montoTotalRetenido = 0;

        foreach ($alquileresVencidos as $alquiler) {
            $this->line("\n  🏷️  Alquiler: {$alquiler->numero_contrato} - Cliente: {$alquiler->cliente->nombres}");
            $this->line("      Fecha devolución programada: {$alquiler->fecha_devolucion_programada}");

            foreach ($alquiler->garantiasIndividuales()->activas()->get() as $garantia) {
                $mensaje = "    • {$garantia->producto->nombre} - Garantía: \${$garantia->monto_garantia_total}";

                if ($this->option('dry-run')) {
                    $this->line($mensaje . ' [SIMULACIÓN - SE RETENDRÍA]');
                    $totalGarantiasRetenidas++;
                    $montoTotalRetenido += $garantia->monto_garantia_total;
                } else {
                    try {
                        $garantia->registrarDevolucion(
                            'PERDIDA',
                            [
                                'condicion' => 'no_devuelta',
                                'motivo' => 'Vencimiento automático del plazo de devolución',
                                'costo_reparacion' => $garantia->monto_garantia_total,
                                'descuento_aplicado' => 0,
                            ],
                            1, // Usuario sistema
                            "Garantía retenida automáticamente por vencimiento de {$diasVencimiento} días"
                        );

                        $this->line($mensaje . ' ✅ RETENIDA');
                        $totalGarantiasRetenidas++;
                        $montoTotalRetenido += $garantia->monto_garantia_total;
                    } catch (\Exception $e) {
                        $this->error($mensaje . " ❌ Error: {$e->getMessage()}");
                    }
                }
            }

            // Actualizar estado del alquiler
            if (!$this->option('dry-run')) {
                $alquiler->update([
                    'estado' => 'VENCIDO',
                    'fecha_devolucion_real' => now(),
                    'observaciones' => ($alquiler->observaciones ?? '') . "\nAlquiler marcado como vencido automáticamente."
                ]);
            }
        }

        $accion = $this->option('dry-run') ? 'se retendrían' : 'retenidas';
        $this->info("\n🎉 Resumen:");
        $this->info("   💰 Total de garantías {$accion}: {$totalGarantiasRetenidas}");
        $this->info("   💵 Monto total {$accion}: \${$montoTotalRetenido}");

        return 0;
    }
}