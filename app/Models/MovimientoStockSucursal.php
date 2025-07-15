<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoStockSucursal extends Model
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

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_movimiento', $tipo);
    }

    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin]);
    }
}
