<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
    public function usuarios()
    {
        return $this->hasMany(User::class);
    }
    public function stockProductos()
    {
        return $this->hasMany(StockPorSucursal::class);
    }
  

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function stockPorSucursales()
    {
        return $this->hasMany(StockPorSucursal::class);
    }

    public function transferenciasOrigen()
    {
        return $this->hasMany(TransferenciaSucursal::class, 'sucursal_origen_id');
    }

    public function transferenciasDestino()
    {
        return $this->hasMany(TransferenciaSucursal::class, 'sucursal_destino_id');
    }

    public function movimientosStock()
    {
        return $this->hasMany(MovimientoStockSucursal::class);
    }

    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
}
