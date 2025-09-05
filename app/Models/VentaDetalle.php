<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'descuento_unitario',
        'subtotal',
        'nombre_producto',
        'codigo_producto',
        'estado'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'descuento_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    // Relaciones
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Accessors
    public function getPrecioConDescuentoAttribute()
    {
        return $this->precio_unitario - $this->descuento_unitario;
    }

    public function getTotalLineaAttribute()
    {
        return $this->cantidad * $this->precio_con_descuento;
    }

    // Métodos de negocio
    public function calcularSubtotal()
    {
        $this->subtotal = $this->cantidad * ($this->precio_unitario - $this->descuento_unitario);
        return $this->subtotal;
    }

    public function aplicarDescuento($descuento)
    {
        $this->descuento_unitario = $descuento;
        $this->calcularSubtotal();
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estado', 'ACTIVO');
    }

    public function scopeCancelados($query)
    {
        return $query->where('estado', 'CANCELADO');
    }
}
