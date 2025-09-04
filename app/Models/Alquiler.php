<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alquiler extends Model
{
    use HasFactory;

    protected $table = 'alquileres';

    protected $fillable = [
        'sucursal_id',
        'numero_contrato',
        'reserva_id',
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
        'total',
        'anticipo',
        'saldo_pendiente',
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
        'total' => 'decimal:2',
        'anticipo' => 'decimal:2',
        'saldo_pendiente' => 'decimal:2',
        'penalizacion' => 'decimal:2',
        'comision_vendedor' => 'decimal:2',
    ];

    // Relaciones
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
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
}