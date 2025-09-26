<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FleteProgramado;

class NotificarFletesPendientes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vestimenta:notificar-fletes-pendientes {--hours=24 : Horas de anticipación para notificar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifica sobre fletes programados que deben ejecutarse pronto';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $horasAnticipacion = $this->option('hours');
        $fechaLimite = now()->addHours($horasAnticipacion);

        $this->info("🚚 Revisando fletes programados para las próximas {$horasAnticipacion} horas...");

        $fletesPendientes = FleteProgramado::where('estado_flete', 'PROGRAMADO')
                                         ->where(function($query) use ($fechaLimite) {
                                             $query->where('fecha_entrega_programada', '<=', $fechaLimite)
                                                   ->orWhere('fecha_recogida_programada', '<=', $fechaLimite);
                                         })
                                         ->with(['alquiler.cliente', 'reserva.cliente', 'evento'])
                                         ->orderBy('fecha_entrega_programada')
                                         ->get();

        if ($fletesPendientes->isEmpty()) {
            $this->info('✅ No hay fletes pendientes para las próximas horas.');
            return 0;
        }

        $this->info("📋 Encontrados {$fletesPendientes->count()} fletes pendientes:");

        foreach ($fletesPendientes as $flete) {
            $this->line("\n  📦 Flete: {$flete->numero_flete}");
            $this->line("      Tipo: {$flete->tipo_flete}");

            // Información del cliente/evento
            if ($flete->alquiler) {
                $this->line("      Cliente: {$flete->alquiler->cliente->nombres} {$flete->alquiler->cliente->apellidos}");
                $this->line("      Contrato: {$flete->alquiler->numero_contrato}");
            } elseif ($flete->reserva) {
                $this->line("      Cliente: {$flete->reserva->cliente->nombres} {$flete->reserva->cliente->apellidos}");
                $this->line("      Reserva: {$flete->reserva->numero_reserva}");
            } elseif ($flete->evento) {
                $this->line("      Evento: {$flete->evento->nombre_evento}");
                $this->line("      Institución: {$flete->evento->institucion_organizadora}");
            }

            // Detalles de entrega
            if ($flete->es_entrega && $flete->fecha_entrega_programada) {
                $tiempoRestante = now()->diffForHumans($flete->fecha_entrega_programada, true);
                $urgencia = $flete->fecha_entrega_programada <= now()->addHours(4) ? '🔴 URGENTE' : '🟡';

                $this->line("      📤 Entrega: {$flete->fecha_entrega_programada} (en {$tiempoRestante}) {$urgencia}");
                $this->line("         Dirección: {$flete->direccion_entrega}");
                $this->line("         Contacto: {$flete->contacto_entrega} - {$flete->telefono_entrega}");
            }

            // Detalles de recogida
            if ($flete->es_recogida && $flete->fecha_recogida_programada) {
                $tiempoRestante = now()->diffForHumans($flete->fecha_recogida_programada, true);
                $urgencia = $flete->fecha_recogida_programada <= now()->addHours(4) ? '🔴 URGENTE' : '🟡';

                $this->line("      📥 Recogida: {$flete->fecha_recogida_programada} (en {$tiempoRestante}) {$urgencia}");
                $this->line("         Dirección: {$flete->direccion_recogida}");
                $this->line("         Contacto: {$flete->contacto_recogida} - {$flete->telefono_recogida}");
            }

            $this->line("      💰 Costo total: \${$flete->costo_total}");
        }

        // Resumen por urgencia
        $urgentes = $fletesPendientes->filter(function($flete) {
            return ($flete->fecha_entrega_programada && $flete->fecha_entrega_programada <= now()->addHours(4)) ||
                   ($flete->fecha_recogida_programada && $flete->fecha_recogida_programada <= now()->addHours(4));
        });

        if ($urgentes->count() > 0) {
            $this->error("\n🚨 ¡ATENCIÓN! {$urgentes->count()} fletes requieren atención URGENTE (próximas 4 horas)");
        }

        $this->info("\n📊 Resumen:");
        $this->info("   📦 Total fletes pendientes: {$fletesPendientes->count()}");
        $this->info("   🔴 Urgentes (< 4 horas): {$urgentes->count()}");
        $this->info("   💰 Valor total programado: \${$fletesPendientes->sum('costo_total')}");

        return 0;
    }
}