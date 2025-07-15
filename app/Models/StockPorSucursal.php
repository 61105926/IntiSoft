<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockPorSucursal extends Model
{
    use HasFactory;
protected $guarded = [];


    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function getStockDisponibleAttribute()
    {
        return $this->stock_actual - $this->stock_reservado - $this->stock_alquilado;
    }

    public function getEstadoStockAttribute()
    {
        if ($this->stock_actual <= 0) {
            return 'SIN_STOCK';
        } elseif ($this->stock_actual <= $this->stock_minimo) {
            return 'STOCK_BAJO';
        }
        return 'STOCK_OK';
    }

    public function getValorStockAttribute()
    {
        return $this->stock_actual * ($this->precio_venta_sucursal ?? $this->producto->precio_venta);
    }

    public function scopeConStockBajo($query)
    {
        return $query->whereRaw('stock_actual <= stock_minimo');
    }

    public function scopeSinStock($query)
    {
        return $query->where('stock_actual', '<=', 0);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
