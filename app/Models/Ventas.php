<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class,'venta_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Client::class,'client_id');
    }
}
