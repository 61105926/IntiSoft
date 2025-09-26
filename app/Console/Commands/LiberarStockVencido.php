<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ReservaStockTemporal;
use App\Models\Reserva;

class LiberarStockVencido extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vestimenta:liberar-stock-vencido {--dry-run : Solo mostrar qué se liberaría sin ejecutar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Libera automáticamente el stock de reservas temporales vencidas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Iniciando liberación de stock vencido...');

        $reservasVencidas = ReservaStockTemporal::where('estado', 'ACTIVA')
                                              ->where('fecha_fin', '<', now()->toDateString())
                                              ->with(['reserva', 'producto', 'sucursal'])
                                              ->get();

        if ($reservasVencidas->isEmpty()) {
            $this->info('✅ No hay reservas de stock vencidas.');
            return 0;
        }

        $this->info("📋 Encontradas {$reservasVencidas->count()} reservas de stock vencidas:");

        $totalLiberado = 0;

        foreach ($reservasVencidas as $reserva) {
            $mensaje = "  • {$reserva->producto->nombre} - Cantidad: {$reserva->cantidad_reservada} - Sucursal: {$reserva->sucursal->nombre} - Vencida: {$reserva->fecha_fin}";

            if ($this->option('dry-run')) {
                $this->line($mensaje . ' [SIMULACIÓN]');
                $totalLiberado += $reserva->cantidad_reservada;
            } else {
                try {
                    $reserva->liberar("Liberada automáticamente por vencimiento");
                    $this->line($mensaje . ' ✅');
                    $totalLiberado += $reserva->cantidad_reservada;

                    // Actualizar estado de la reserva padre si corresponde
                    if ($reserva->reserva && $reserva->reserva->estado === 'ACTIVA') {
                        $reserva->reserva->update(['estado' => 'VENCIDA']);
                    }
                } catch (\Exception $e) {
                    $this->error($mensaje . " ❌ Error: {$e->getMessage()}");
                }
            }
        }

        $accion = $this->option('dry-run') ? 'se liberarían' : 'liberadas';
        $this->info("🎉 Total de unidades {$accion}: {$totalLiberado}");

        return 0;
    }
}