<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EntradaFolcloricaDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'entrada_folclorica_id',
        'producto_id',
        'codigo_producto',
        'nombre_producto',
        'descripcion_producto',
        'talla',
        'color',
        'nombre_participante',
        'telefono_participante',
        'talla_solicitada',
        'observaciones_participante',
        'cantidad',
        'precio_unitario',
        'descuento_unitario',
        'subtotal',
        'estado',
        'fecha_entrega_individual',
        'fecha_devolucion_individual',
        'observaciones_entrega',
        'observaciones_devolucion',
        'penalizacion',
        'motivo_penalizacion',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'descuento_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'penalizacion' => 'decimal:2',
        'fecha_entrega_individual' => 'datetime',
        'fecha_devolucion_individual' => 'datetime',
    ];

    // Estados disponibles
    const ESTADO_PENDIENTE = 'PENDIENTE';
    const ESTADO_ENTREGADO = 'ENTREGADO';
    const ESTADO_DEVUELTO = 'DEVUELTO';
    const ESTADO_DEVUELTO_PARCIAL = 'DEVUELTO_PARCIAL';

    // Relaciones
    public function entradaFolclorica()
    {
        return $this->belongsTo(EntradaFolclorica::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function garantia()
    {
        return $this->hasOne(EntradaFolcloricaGarantia::class, 'entrada_detalle_id');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    public function scopeEntregados($query)
    {
        return $query->where('estado', self::ESTADO_ENTREGADO);
    }

    public function scopeDevueltos($query)
    {
        return $query->whereIn('estado', [self::ESTADO_DEVUELTO, self::ESTADO_DEVUELTO_PARCIAL]);
    }

    // Accessors
    public function getEstadoDisplayAttribute()
    {
        return match($this->estado) {
            self::ESTADO_PENDIENTE => 'Pendiente',
            self::ESTADO_ENTREGADO => 'Entregado',
            self::ESTADO_DEVUELTO => 'Devuelto',
            self::ESTADO_DEVUELTO_PARCIAL => 'Devuelto Parcial',
            default => $this->estado
        };
    }

    public function getEstadoBadgeClassAttribute()
    {
        return match($this->estado) {
            self::ESTADO_PENDIENTE => 'bg-warning',
            self::ESTADO_ENTREGADO => 'bg-success',
            self::ESTADO_DEVUELTO => 'bg-info',
            self::ESTADO_DEVUELTO_PARCIAL => 'bg-secondary',
            default => 'bg-light'
        };
    }

    public function getEsDevueltoAttribute()
    {
        return in_array($this->estado, [self::ESTADO_DEVUELTO, self::ESTADO_DEVUELTO_PARCIAL]);
    }

    public function getTieneGarantiaAttribute()
    {
        return $this->garantia()->exists();
    }

    // Métodos de negocio
    public function marcarComoEntregado($observaciones = null)
    {
        $this->estado = self::ESTADO_ENTREGADO;
        $this->fecha_entrega_individual = Carbon::now();
        $this->observaciones_entrega = $observaciones;
        $this->save();
    }

    public function marcarComoDevuelto($observaciones = null, $penalizacion = 0, $motivoPenalizacion = null)
    {
        $this->estado = self::ESTADO_DEVUELTO;
        $this->fecha_devolucion_individual = Carbon::now();
        $this->observaciones_devolucion = $observaciones;
        $this->penalizacion = $penalizacion;
        $this->motivo_penalizacion = $motivoPenalizacion;
        $this->save();
        
        // Actualizar la garantía si hay penalización
        if ($penalizacion > 0 && $this->garantia) {
            $this->garantia->aplicarPenalizacion($penalizacion, $motivoPenalizacion);
        }
    }

    public function calcularSubtotal()
    {
        $this->subtotal = ($this->cantidad * $this->precio_unitario) - $this->descuento_unitario;
        return $this->subtotal;
    }
}