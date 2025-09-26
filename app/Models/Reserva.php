<?php

namespace App\Models;

use App\Traits\CalculosFinancieros;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory, CalculosFinancieros;
    protected $fillable = [
        'numero_reserva',
        'cliente_id',
        'tipo_reserva',
        'fecha_reserva',
        'fecha_vencimiento',
        'anticipo',
        'subtotal',
        'descuento',
        'impuestos',
        'costos_adicionales',
        'detalle_costos_adicionales',
        'total',
        'observaciones',
        'sucursal_id',
        'usuario_creacion_id',
        'estado',
    ];

    protected $casts = [
        'fecha_reserva' => 'date',
        'fecha_vencimiento' => 'date',
        'anticipo' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'impuestos' => 'decimal:2',
        'costos_adicionales' => 'decimal:2',
        'total' => 'decimal:2',
        'detalle_costos_adicionales' => 'array',
    ];

    // Asegurar que saldoPendiente siempre use el método del trait
    protected $appends = ['saldo_pendiente'];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function usuarioCreacion()
    {
        return $this->belongsTo(User::class, 'usuario_creacion_id');
    }

    public function detalles()
    {
        return $this->hasMany(ReservaDetalle::class);
    }

    public function alquiler()
    {
        return $this->hasOne(Alquiler::class);
    }

    public function stocksTemporales()
    {
        return $this->hasMany(ReservaStockTemporal::class);
    }

    public function fletes()
    {
        return $this->hasMany(FleteProgramado::class);
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('estado', 'ACTIVA');
    }

    public function scopeProximasVencer($query)
    {
        return $query->where('estado', 'PROXIMA_VENCER');
    }

    public function scopeVencidas($query)
    {
        return $query->where('estado', 'VENCIDA');
    }

    public function scopeConfirmadas($query)
    {
        return $query->where('estado', 'CONFIRMADA');
    }

    // Accessors
    public function getDiasRestantesAttribute()
    {
        return $this->fecha_vencimiento->diffInDays(now(), false);
    }

    public function getSaldoPendienteAttribute()
    {
        return $this->calcularSaldoPendiente();
    }

    public function actualizarEstadoPago()
    {
        if ($this->estaCompletamentePagado()) {
            // Lógica adicional si necesitas un estado de pago en reservas
        }
        return $this;
    }

    // Mutators y métodos
    public function actualizarEstado()
    {
        $diasRestantes = $this->dias_restantes;

        if ($diasRestantes <= 0 && $this->estado === 'ACTIVA') {
            $this->update(['estado' => 'VENCIDA']);
        } elseif ($diasRestantes <= 2 && $this->estado === 'ACTIVA') {
            $this->update(['estado' => 'PROXIMA_VENCER']);
        }
    }

    public function puedeConvertirseAAlquiler()
    {
        return in_array($this->estado, ['ACTIVA', 'PROXIMA_VENCER']) && 
               $this->tipo_reserva === 'ALQUILER' && 
               !$this->alquiler;
    }

    public function convertirAAlquiler($datosAlquiler = [])
    {
        if (!$this->puedeConvertirseAAlquiler()) {
            throw new \Exception('La reserva no puede convertirse a alquiler.');
        }

        // Recalcular totales antes de la conversión
        $this->actualizarCalculosFinancieros();

        // Calcular ajustes si hay cambios en precios
        $ajuste_conversion = 0;
        $motivo_ajuste = '';
        
        if (isset($datosAlquiler['nuevo_subtotal'])) {
            $ajuste_conversion = $datosAlquiler['nuevo_subtotal'] - $this->subtotal;
            $motivo_ajuste = $ajuste_conversion > 0 ? 'Incremento en precios' : 'Descuento adicional';
        }

        // Datos por defecto del alquiler basados en la reserva
        $datosAlquiler = array_merge([
            'sucursal_id' => $this->sucursal_id,
            'numero_contrato' => 'ALQ-' . date('Y') . '-' . str_pad(Alquiler::count() + 1, 6, '0', STR_PAD_LEFT),
            'reserva_id' => $this->id,
            'cliente_id' => $this->cliente_id,
            'fecha_alquiler' => $datosAlquiler['fecha_alquiler'] ?? now()->toDateString(),
            'hora_entrega' => $datosAlquiler['hora_entrega'] ?? '09:00:00',
            'fecha_devolucion_programada' => $datosAlquiler['fecha_devolucion_programada'] ?? now()->addDays(3)->toDateString(),
            'hora_devolucion_programada' => $datosAlquiler['hora_devolucion_programada'] ?? '18:00:00',
            'dias_alquiler' => $datosAlquiler['dias_alquiler'] ?? 3,
            
            // Heredar valores financieros de la reserva
            'subtotal' => $datosAlquiler['nuevo_subtotal'] ?? $this->subtotal,
            'descuento' => $this->descuento,
            'impuestos' => $this->impuestos,
            'costos_adicionales' => $this->costos_adicionales + ($datosAlquiler['costos_adicionales_extra'] ?? 0),
            'total' => $this->total + $ajuste_conversion, // Total con ajuste
            'detalle_costos_adicionales' => $this->detalle_costos_adicionales,
            
            // Gestión de anticipos
            'anticipo_reserva' => $this->anticipo,
            'anticipo' => $datosAlquiler['anticipo'] ?? 0, // Anticipo adicional al convertir
            'ajuste_conversion' => $ajuste_conversion,
            'motivo_ajuste' => $motivo_ajuste,
            
            // Otros campos
            'usuario_creacion' => $datosAlquiler['usuario_creacion'] ?? $this->usuario_creacion_id,
            'estado' => 'ACTIVO',
            'observaciones' => "Convertido desde reserva {$this->numero_reserva}. " . ($this->observaciones ?? ''),
            
            // Depósito de garantía si es requerido
            'requiere_deposito' => $datosAlquiler['requiere_deposito'] ?? false,
            'deposito_garantia' => $datosAlquiler['deposito_garantia'] ?? 0,
        ], $datosAlquiler);

        // Crear el alquiler
        $alquiler = Alquiler::create($datosAlquiler);

        // Transferir detalles de reserva a alquiler con posibles ajustes
        foreach ($this->detalles as $detalle) {
            // Permitir ajustes de precio por producto si se especifican
            $nuevoPrecio = $datosAlquiler['ajustes_productos'][$detalle->producto_id]['precio_unitario'] ?? $detalle->precio_unitario;
            $nuevaCantidad = $datosAlquiler['ajustes_productos'][$detalle->producto_id]['cantidad'] ?? $detalle->cantidad;
            
            $alquiler->detalles()->create([
                'producto_id' => $detalle->producto_id,
                'cantidad' => $nuevaCantidad,
                'precio_unitario' => $nuevoPrecio,
                'subtotal' => $nuevoPrecio * $nuevaCantidad,
                'estado_devolucion' => 'PENDIENTE',
            ]);
        }

        // Actualizar cálculos del alquiler
        $alquiler->actualizarCalculosFinancieros();
        
        // Determinar estado de pago
        $alquiler->actualizarEstadoPago();

        // Actualizar estado de la reserva
        $this->update(['estado' => 'CONFIRMADA']);

        return $alquiler;
    }

    public function esConvertible()
    {
        return $this->puedeConvertirseAAlquiler();
    }
}
