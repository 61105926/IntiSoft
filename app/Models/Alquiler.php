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
        'garantia_id',
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

    public function garantia()
    {
        return $this->belongsTo(Garantia::class);
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

    // Métodos para garantías
    public function tieneGarantia()
    {
        return !is_null($this->garantia_id) && $this->garantia;
    }

    public function asignarGarantia($garantiaId)
    {
        $garantia = Garantia::find($garantiaId);
        
        if (!$garantia || !$garantia->puede_usarse) {
            throw new \Exception('La garantía no está disponible para su uso.');
        }

        $this->update(['garantia_id' => $garantiaId]);
        
        return $this;
    }

    public function liberarGarantia($motivo = null)
    {
        if ($this->garantia) {
            $observaciones = "Garantía liberada del alquiler {$this->numero_contrato}";
            if ($motivo) {
                $observaciones .= " - Motivo: {$motivo}";
            }
            
            $this->garantia->update([
                'observaciones' => $this->garantia->observaciones . "\n{$observaciones}"
            ]);
        }
        
        $this->update(['garantia_id' => null]);
        
        return $this;
    }

    public function aplicarGarantia($monto, $motivo = null)
    {
        if (!$this->tieneGarantia()) {
            throw new \Exception('Este alquiler no tiene garantía asignada.');
        }

        $motivoCompleto = "Aplicado a alquiler {$this->numero_contrato}";
        if ($motivo) {
            $motivoCompleto .= " - {$motivo}";
        }

        $this->garantia->aplicarMonto($monto, $motivoCompleto);
        
        return $this;
    }

    public function getInfoGarantiaAttribute()
    {
        if (!$this->tieneGarantia()) {
            return null;
        }

        return [
            'numero_ticket' => $this->garantia->numero_ticket,
            'tipo' => $this->garantia->tipoGarantia->nombre,
            'monto' => $this->garantia->monto,
            'monto_disponible' => $this->garantia->monto_disponible,
            'estado' => $this->garantia->estado_display,
            'fecha_vencimiento' => $this->garantia->fecha_vencimiento,
        ];
    }
}