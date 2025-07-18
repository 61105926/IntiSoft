<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $guarded = [];

    use HasFactory;
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaProducto::class, 'categoria_id');
    }
    public function stocks()
    {
        return $this->hasMany(StockPorSucursal::class, 'producto_id');
    }
    public function creador()
    {
        return $this->belongsTo(User::class, 'usuario_creacion');
    }

    public function stockPorSucursal()
    {
        return $this->hasOne(StockPorSucursal::class)->where('sucursal_id', $this->sucursal_id);
    }

    public function movimientosStock()
    {
        return $this->hasMany(MovimientoStockSucursal::class);
    }

    public function detalleTransferencias()
    {
        return $this->hasMany(DetalleTransferencia::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function getStockEnSucursal($sucursalId)
    {
        return $this->stockPorSucursal()->where('sucursal_id', $sucursalId)->first();
    }

    public function getTotalStock()
    {
        return $this->stockPorSucursal()->sum('stock_actual');
    }
}
