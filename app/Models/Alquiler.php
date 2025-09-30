<?php

namespace App\Models;

use App\Traits\CalculosFinancieros;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alquiler extends Model
{
    use HasFactory, CalculosFinancieros;

    protected $table = 'alquileres';

    protected $fillable = [
        'sucursal_id',
        'numero_contrato',
        'cliente_id',
        'unidad_educativa_id',
        'tipo_pago_id',
        'fecha_alquiler',
        'hora_entrega',
        'fecha_devolucion_programada',
        'hora_devolucion_programada',
        'fecha_devolucion_real',
        'dias_alquiler',
        'subtotal',
        'descuento',
        'impuestos',
        'costos_adicionales',
        'detalle_costos_adicionales',
        'deposito_garantia',
        'deposito_devuelto',
        'requiere_deposito',
        'total',
        'anticipo',
        'ajuste_conversion',
        'motivo_ajuste',
        'penalizacion',
        'comision_vendedor',
        'estado',
        'estado_pago',
        'referencia_pago',
        'lugar_entrega',
        'lugar_devolucion',
        'observaciones',
        'condiciones_especiales',
        'usuario_creacion',
        'usuario_entrega',
        'usuario_devolucion',
        'tipo_garantia',
        'documento_garantia',
        'monto_garantia',
        'observaciones_garantia',
        'estado_garantia',
        'fecha_devolucion_garantia',
        'monto_devuelto_garantia',
    ];

    protected $dates = [
        'fecha_alquiler',
        'fecha_devolucion_programada',
        'fecha_devolucion_real',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'fecha_alquiler' => 'date',
        'fecha_devolucion_programada' => 'date',
        'fecha_devolucion_real' => 'datetime',
        'fecha_devolucion_garantia' => 'datetime',
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'impuestos' => 'decimal:2',
        'costos_adicionales' => 'decimal:2',
        'deposito_garantia' => 'decimal:2',
        'deposito_devuelto' => 'decimal:2',
        'total' => 'decimal:2',
        'anticipo' => 'decimal:2',
        'ajuste_conversion' => 'decimal:2',
        'saldo_pendiente' => 'decimal:2',
        'penalizacion' => 'decimal:2',
        'comision_vendedor' => 'decimal:2',
        'monto_garantia' => 'decimal:2',
        'monto_devuelto_garantia' => 'decimal:2',
        'requiere_deposito' => 'boolean',
        'detalle_costos_adicionales' => 'array',
    ];

    // Asegurar que saldoPendiente siempre use el método del trait
    protected $appends = ['saldo_pendiente'];

    // Relaciones
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }


    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function unidadEducativa()
    {
        return $this->belongsTo(UnidadEducativa::class);
    }

    public function usuarioCreacion()
    {
        return $this->belongsTo(User::class, 'usuario_creacion');
    }

    public function usuarioEntrega()
    {
        return $this->belongsTo(User::class, 'usuario_entrega');
    }

    public function usuarioDevolucion()
    {
        return $this->belongsTo(User::class, 'usuario_devolucion');
    }

    public function detalles()
    {
        return $this->hasMany(AlquilerDetalle::class);
    }

    public function garantiasIndividuales()
    {
        return $this->hasMany(GarantiaIndividual::class);
    }


    public function eventoFolklorico()
    {
        return $this->belongsTo(EventoFolklorico::class, 'evento_folklorico_id');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estado', 'ACTIVO');
    }

    public function scopeVencidos($query)
    {
        return $query->where('estado', 'VENCIDO')
                    ->orWhere('fecha_devolucion_programada', '<', now());
    }

    public function scopePorSucursal($query, $sucursalId)
    {
        return $query->where('sucursal_id', $sucursalId);
    }

    // Accessors
    public function getDiasVencidosAttribute()
    {
        if ($this->estado === 'DEVUELTO') {
            return 0;
        }
        
        $fechaLimite = $this->fecha_devolucion_programada;
        $hoy = now()->startOfDay();
        
        if ($hoy->gt($fechaLimite)) {
            return $hoy->diffInDays($fechaLimite);
        }
        
        return 0;
    }

    public function getEstadoDisplayAttribute()
    {
        $estados = [
            'ACTIVO' => 'Activo',
            'DEVUELTO' => 'Devuelto',
            'VENCIDO' => 'Vencido',
            'CANCELADO' => 'Cancelado',
            'PARCIAL' => 'Parcial'
        ];

        return $estados[$this->estado] ?? $this->estado;
    }

    public function getEstadoPagoDisplayAttribute()
    {
        $estados = [
            'PENDIENTE' => 'Pendiente',
            'PARCIAL' => 'Parcial',
            'PAGADO' => 'Pagado',
            'VENCIDO' => 'Vencido'
        ];

        return $estados[$this->estado_pago] ?? $this->estado_pago;
    }

    public function getSaldoPendienteAttribute()
    {
        return $this->calcularSaldoPendiente();
    }

    // Métodos específicos de alquiler
    public function actualizarEstadoPago()
    {
        if ($this->estaCompletamentePagado()) {
            $this->estado_pago = 'PAGADO';
        } elseif ($this->anticipo > 0) {
            $this->estado_pago = 'PARCIAL';
        } else {
            $this->estado_pago = 'PENDIENTE';
        }
        
        // Verificar vencimiento
        if ($this->fecha_devolucion_programada < now() && $this->estado_pago !== 'PAGADO') {
            $this->estado_pago = 'VENCIDO';
        }
        
        $this->save();
        return $this;
    }

    public function manejarDeposito($accion, $monto = null)
    {
        switch ($accion) {
            case 'cobrar':
                if (!$this->requiere_deposito) {
                    throw new \Exception('Este alquiler no requiere depósito.');
                }
                $this->aplicarCostoAdicional($this->deposito_garantia, 'Depósito de garantía');
                break;
                
            case 'devolver':
                $montoDevolucion = $monto ?? $this->deposito_garantia;
                $this->deposito_devuelto = $montoDevolucion;
                if ($montoDevolucion < $this->deposito_garantia) {
                    $diferencia = $this->deposito_garantia - $montoDevolucion;
                    $this->aplicarPenalizacion($diferencia, 'Retención de depósito');
                }
                break;
        }
        
        $this->actualizarCalculosFinancieros();
        return $this;
    }

    public function completarDevolucion($observaciones = null)
    {
        $todoDevuelto = $this->detalles->every(function ($detalle) {
            return $detalle->estado_devolucion !== 'PENDIENTE';
        });

        if ($todoDevuelto) {
            $this->update([
                'estado' => 'DEVUELTO',
                'fecha_devolucion_real' => now(),
                'usuario_devolucion' => auth()->id() ?? $this->usuario_creacion,
                'observaciones' => $this->observaciones . ($observaciones ? ' | ' . $observaciones : ''),
            ]);
        } else {
            $this->update(['estado' => 'PARCIAL']);
        }

        return $this;
    }

    public function aplicarPenalizacion($monto, $motivo = null)
    {
        $this->penalizacion += $monto;
        $this->observaciones = $this->observaciones . ' | Penalización: ' . $motivo . ' ($' . $monto . ')';
        $this->calcularTotales();
        
        return $this;
    }

    public function esRenovable()
    {
        return in_array($this->estado, ['ACTIVO', 'VENCIDO']) && 
               $this->detalles->where('estado_devolucion', 'PENDIENTE')->count() > 0;
    }

    public function renovar($nuevaFechaDevolucion, $diasAdicionales = 0)
    {
        if (!$this->esRenovable()) {
            throw new \Exception('El alquiler no puede renovarse.');
        }

        $this->fecha_devolucion_programada = $nuevaFechaDevolucion;
        $this->dias_alquiler += $diasAdicionales;
        
        if ($this->estado === 'VENCIDO') {
            $this->estado = 'ACTIVO';
        }
        
        $this->save();
        return $this;
    }

    // Métodos para garantías integradas
    public function tieneGarantia()
    {
        return $this->tipo_garantia !== 'NINGUNA';
    }

    public function devolverGarantia($montoDevuelto = null, $observaciones = null)
    {
        if (!$this->tieneGarantia()) {
            throw new \Exception('Este alquiler no tiene garantía.');
        }

        $montoDevuelto = $montoDevuelto ?? $this->monto_garantia;

        $this->update([
            'estado_garantia' => 'DEVUELTA',
            'fecha_devolucion_garantia' => now(),
            'monto_devuelto_garantia' => $montoDevuelto,
            'observaciones_garantia' => $observaciones
        ]);

        // Si es CI, no hay monto monetario devuelto
        if ($this->tipo_garantia === 'CI') {
            $this->monto_devuelto_garantia = 0.00;
        }

        return $this;
    }

    public function aplicarGarantia($motivo = null)
    {
        if (!$this->tieneGarantia()) {
            throw new \Exception('Este alquiler no tiene garantía.');
        }

        $this->update([
            'estado_garantia' => 'APLICADA',
            'fecha_devolucion_garantia' => now(),
            'monto_devuelto_garantia' => 0,
            'observaciones_garantia' => $motivo ?? 'Garantía aplicada por daños/pérdidas'
        ]);

        // Si es efectivo o QR, aplicar el monto como pago
        if (in_array($this->tipo_garantia, ['EFECTIVO', 'QR'])) {
            $this->anticipo += $this->monto_garantia;
            $this->actualizarEstadoPago();
        }

        return $this;
    }

    public function getTipoGarantiaDisplayAttribute()
    {
        $tipos = [
            'NINGUNA' => 'Sin garantía',
            'CI' => 'Cédula de Identidad',
            'EFECTIVO' => 'Efectivo',
            'QR' => 'QR/Transferencia'
        ];

        return $tipos[$this->tipo_garantia] ?? $this->tipo_garantia;
    }

    public function getEstadoGarantiaDisplayAttribute()
    {
        $estados = [
            'PENDIENTE' => 'Pendiente',
            'DEVUELTA' => 'Devuelta',
            'APLICADA' => 'Aplicada'
        ];

        return $estados[$this->estado_garantia] ?? $this->estado_garantia;
    }

    public function getInfoGarantiaAttribute()
    {
        if (!$this->tieneGarantia()) {
            return null;
        }

        return [
            'tipo' => $this->tipo_garantia_display,
            'documento' => $this->documento_garantia,
            'monto' => $this->monto_garantia,
            'estado' => $this->estado_garantia_display,
            'monto_devuelto' => $this->monto_devuelto_garantia,
            'observaciones' => $this->observaciones_garantia,
            'fecha_devolucion' => $this->fecha_devolucion_garantia,
        ];
    }
}