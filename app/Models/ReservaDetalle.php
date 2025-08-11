<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservaDetalle extends Model
{
    use HasFactory;
    protected $fillable = [
        'reserva_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relaciones
    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
