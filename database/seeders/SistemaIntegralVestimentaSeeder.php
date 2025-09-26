<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reserva;
use App\Models\Alquiler;
use App\Models\ReservaStockTemporal;
use App\Models\GarantiaIndividual;
use App\Models\FleteProgramado;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\User;
use App\Services\GestionIntegralVestimentaService;
use Carbon\Carbon;

class SistemaIntegralVestimentaSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->command->info('🎭 Creando datos de prueba para el Sistema Integral de Vestimenta...');

        $gestionService = new GestionIntegralVestimentaService();
        $sucursal = Sucursal::first();
        $usuario = User::first();
        $clientes = Cliente::take(3)->get();
        $productos = Producto::where('activo', true)->take(3)->get();

        if (!$sucursal || !$usuario || $clientes->count() < 3 || $productos->count() < 3) {
            $this->command->warn('❌ Faltan datos básicos. Ejecute primero BasicDataSeeder.');
            return;
        }

        // 1. Crear reservas con control temporal de stock
        $this->command->info('📅 Creando reservas con control temporal de stock...');

        $fechaInicio = Carbon::now()->addDays(5);
        $fechaFin = Carbon::now()->addDays(8);

        $reservaData = [
            'numero_reserva' => 'RES-INTEG-' . date('Y') . '-001',
            'cliente_id' => $clientes[0]->id,
            'tipo_reserva' => 'ALQUILER',
            'fecha_reserva' => Carbon::now(),
            'fecha_vencimiento' => Carbon::now()->addDays(3),
            'anticipo' => 150.00,
            'sucursal_id' => $sucursal->id,
            'usuario_creacion_id' => $usuario->id,
            'estado' => 'ACTIVA',
        ];

        $productosReserva = [
            [
                'producto_id' => $productos[0]->id,
                'cantidad' => 1,
                'precio_unitario' => 80.00,
                'subtotal' => 80.00,
            ]
        ];

        try {
            $reserva = $gestionService->crearReservaConStock(
                $reservaData,
                $productosReserva,
                $fechaInicio,
                $fechaFin
            );
            $this->command->info("   ✅ Reserva creada: {$reserva->numero_reserva} con stock temporal bloqueado");
        } catch (\Exception $e) {
            $this->command->error("   ❌ Error creando reserva: {$e->getMessage()}");
        }

        // 2. Crear alquiler directo con garantías individuales
        $this->command->info('🏷️ Creando alquiler con garantías individuales...');

        $alquilerData = [
            'numero_contrato' => 'ALQ-INTEG-' . date('Y') . '-001',
            'cliente_id' => $clientes[1]->id,
            'sucursal_id' => $sucursal->id,
            'fecha_alquiler' => Carbon::now(),
            'fecha_devolucion_programada' => Carbon::now()->addDays(4),
            'dias_alquiler' => 4,
            'subtotal' => 0,
            'total' => 0,
            'usuario_creacion' => $usuario->id,
            'estado' => 'ACTIVO',
        ];

        $alquiler = Alquiler::create($alquilerData);

        // Agregar detalles del alquiler
        $detalleAlquiler1 = $alquiler->detalles()->create([
            'producto_id' => $productos[0]->id,
            'cantidad' => 1,
            'precio_unitario' => 100.00,
            'subtotal' => 100.00,
            'estado_devolucion' => 'PENDIENTE',
        ]);

        $detalleAlquiler2 = $alquiler->detalles()->create([
            'producto_id' => $productos[2]->id,
            'cantidad' => 2,
            'precio_unitario' => 75.00,
            'subtotal' => 150.00,
            'estado_devolucion' => 'PENDIENTE',
        ]);

        // Crear garantías individuales
        $garantia1 = GarantiaIndividual::crearGarantiaIndividual([
            'alquiler_id' => $alquiler->id,
            'producto_id' => $productos[0]->id,
            'cliente_id' => $clientes[1]->id,
            'cantidad' => 1,
            'monto_garantia_unitario' => 200.00,
            'observaciones_entrega' => 'Prenda en excelente estado al momento de entrega',
            'usuario_entrega' => $usuario->id,
        ]);

        $garantia2 = GarantiaIndividual::crearGarantiaIndividual([
            'alquiler_id' => $alquiler->id,
            'producto_id' => $productos[2]->id,
            'cliente_id' => $clientes[1]->id,
            'cantidad' => 2,
            'monto_garantia_unitario' => 150.00,
            'observaciones_entrega' => 'Conjunto completo entregado',
            'usuario_entrega' => $usuario->id,
        ]);

        $alquiler->update([
            'subtotal' => 250.00,
            'total' => 250.00,
            'total_garantias_individuales' => $garantia1->monto_garantia_total + $garantia2->monto_garantia_total,
        ]);

        $this->command->info("   ✅ Alquiler creado: {$alquiler->numero_contrato} con 2 garantías individuales");

        // 3. Crear fletes programados
        $this->command->info('🚚 Programando fletes de entrega y recogida...');

        // Flete para el alquiler
        $flete1 = FleteProgramado::programarFlete([
            'alquiler_id' => $alquiler->id,
            'tipo_flete' => 'AMBOS',
            'direccion_entrega' => 'Av. El Prado 1234, La Paz',
            'referencia_entrega' => 'Edificio Torre Empresarial, Piso 5',
            'fecha_entrega_programada' => Carbon::now()->addHours(2),
            'contacto_entrega' => $clientes[1]->nombres . ' ' . $clientes[1]->apellidos,
            'telefono_entrega' => $clientes[1]->telefono,
            'direccion_recogida' => 'Av. El Prado 1234, La Paz',
            'fecha_recogida_programada' => Carbon::now()->addDays(4)->addHours(10),
            'contacto_recogida' => $clientes[1]->nombres . ' ' . $clientes[1]->apellidos,
            'telefono_recogida' => $clientes[1]->telefono,
            'costo_entrega' => 25.00,
            'costo_recogida' => 30.00,
            'vehiculo_tipo' => 'Camioneta',
            'conductor_nombre' => 'Juan Pérez',
            'conductor_telefono' => '70123456',
            'usuario_programacion' => $usuario->id,
        ]);

        $this->command->info("   ✅ Flete programado: {$flete1->numero_flete} (Entrega y Recogida)");

        // Flete solo de entrega para la reserva (si existe)
        if (isset($reserva)) {
            $flete2 = FleteProgramado::programarFlete([
                'reserva_id' => $reserva->id,
                'tipo_flete' => 'ENTREGA',
                'direccion_entrega' => 'Calle Comercio 567, La Paz',
                'referencia_entrega' => 'Casa color azul, portón negro',
                'fecha_entrega_programada' => $fechaInicio->copy()->subHours(4),
                'contacto_entrega' => $clientes[0]->nombres . ' ' . $clientes[0]->apellidos,
                'telefono_entrega' => $clientes[0]->telefono,
                'costo_entrega' => 20.00,
                'vehiculo_tipo' => 'Motocicleta',
                'conductor_nombre' => 'María López',
                'conductor_telefono' => '75987654',
                'usuario_programacion' => $usuario->id,
            ]);

            $this->command->info("   ✅ Flete programado: {$flete2->numero_flete} (Solo Entrega)");
        }

        // 4. Crear algunas reservas temporales adicionales para pruebas
        $this->command->info('📦 Creando reservas temporales adicionales...');

        ReservaStockTemporal::create([
            'reserva_id' => isset($reserva) ? $reserva->id : 1,
            'producto_id' => $productos[1]->id,
            'sucursal_id' => $sucursal->id,
            'cantidad_reservada' => 1,
            'fecha_inicio' => Carbon::now()->addDays(10),
            'fecha_fin' => Carbon::now()->addDays(12),
            'estado' => 'ACTIVA',
            'observaciones' => 'Reserva temporal de prueba para el futuro',
        ]);

        // 5. Mostrar estadísticas
        $this->command->info("\n📊 Estadísticas del sistema integral:");
        $this->command->info("   📅 Reservas con stock temporal: " . ReservaStockTemporal::count());
        $this->command->info("   🏷️ Garantías individuales: " . GarantiaIndividual::count());
        $this->command->info("   🚚 Fletes programados: " . FleteProgramado::count());
        $this->command->info("   💰 Total garantías activas: $" . GarantiaIndividual::activas()->sum('monto_garantia_total'));

        $this->command->info("\n🎉 ¡Seeder del Sistema Integral completado exitosamente!");
        $this->command->info("🔍 Pruebe los comandos de mantenimiento:");
        $this->command->info("   php artisan vestimenta:liberar-stock-vencido --dry-run");
        $this->command->info("   php artisan vestimenta:notificar-fletes-pendientes");
        $this->command->info("   php artisan vestimenta:procesar-garantias-vencidas --dry-run");
    }
}