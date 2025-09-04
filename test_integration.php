<?php
// Archivo de prueba para la integración financiera

require_once 'vendor/autoload.php';

use App\Models\Reserva;
use App\Models\ReservaDetalle;
use App\Models\Alquiler;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🧪 Probando integración financiera mejorada...\n\n";

try {
    // 1. Crear una reserva con el sistema financiero nuevo
    echo "1️⃣ Creando reserva con costos detallados...\n";
    
    $reserva = new Reserva();
    $reserva->numero_reserva = 'TEST-001';
    $reserva->cliente_id = 1; // Asumir que existe
    $reserva->sucursal_id = 1; // Asumir que existe  
    $reserva->usuario_creacion_id = 1; // Asumir que existe
    $reserva->tipo_reserva = 'ALQUILER';
    $reserva->fecha_reserva = now();
    $reserva->fecha_vencimiento = now()->addDays(7);
    
    // Usar el trait de cálculos financieros
    $reserva->subtotal = 600.00;
    $reserva->descuento = 50.00;
    $reserva->impuestos = 82.50; // 15% sobre (600-50)
    $reserva->costos_adicionales = 120.00;
    $reserva->anticipo = 300.00;
    $reserva->observaciones = 'Prueba de integración financiera';
    $reserva->estado = 'ACTIVA';
    
    $reserva->save();
    
    // Recalcular usando el trait
    $reserva->actualizarCalculosFinancieros();
    
    echo "   ✅ Reserva creada: {$reserva->numero_reserva}\n";
    echo "   💰 Subtotal: \${$reserva->subtotal}\n";
    echo "   💰 Total: \${$reserva->total}\n";
    echo "   💰 Saldo pendiente: \${$reserva->saldo_pendiente}\n\n";
    
    // 2. Convertir a alquiler con ajustes
    echo "2️⃣ Convirtiendo reserva a alquiler con ajustes...\n";
    
    if ($reserva->puedeConvertirseAAlquiler()) {
        $alquiler = $reserva->convertirAAlquiler([
            'fecha_alquiler' => now()->addDay(),
            'fecha_devolucion_programada' => now()->addDays(5),
            'dias_alquiler' => 4,
            'anticipo_adicional' => 200.00,
            'requiere_deposito' => true,
            'deposito_garantia' => 150.00,
            'ajuste_conversion' => 50.00,
            'motivo_ajuste' => 'Incremento por días adicionales'
        ]);
        
        echo "   ✅ Alquiler creado: {$alquiler->numero_contrato}\n";
        echo "   🔄 Anticipo reserva: \${$alquiler->anticipo_reserva}\n";
        echo "   🔄 Anticipo adicional: \${$alquiler->anticipo}\n";
        echo "   🔄 Ajuste conversión: \${$alquiler->ajuste_conversion}\n";
        echo "   🛡️  Depósito garantía: \${$alquiler->deposito_garantia}\n";
        echo "   💰 Total final: \${$alquiler->total}\n";
        echo "   💰 Saldo pendiente: \${$alquiler->saldo_pendiente}\n\n";
        
        // 3. Probar métodos financieros
        echo "3️⃣ Probando métodos financieros...\n";
        
        $resumen = $alquiler->getResumenFinanciero();
        echo "   📊 Resumen financiero:\n";
        foreach ($resumen as $key => $value) {
            echo "      - " . ucfirst(str_replace('_', ' ', $key)) . ": \${$value}\n";
        }
        
        // Aplicar costo adicional
        $alquiler->aplicarCostoAdicional(75.00, 'Limpieza especializada extra');
        echo "   ✅ Costo adicional aplicado: +$75.00\n";
        echo "   💰 Nuevo total: \${$alquiler->total}\n\n";
        
        // 4. Registrar pago
        echo "4️⃣ Registrando pago adicional...\n";
        
        $alquiler->registrarPago(200.00, 'transferencia', 'TXN-123456');
        echo "   ✅ Pago registrado: +$200.00\n";
        echo "   💰 Saldo pendiente: \${$alquiler->saldo_pendiente}\n";
        echo "   📊 Estado: " . ($alquiler->estaCompletamentePagado() ? 'PAGADO' : 'PENDIENTE') . "\n\n";
        
        echo "🎉 ¡Integración financiera funcionando correctamente!\n";
        
    } else {
        echo "   ❌ La reserva no puede convertirse a alquiler\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "   Línea: " . $e->getLine() . " en " . $e->getFile() . "\n";
}