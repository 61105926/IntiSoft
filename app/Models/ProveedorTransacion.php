<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProveedorTransacion extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function provider()
    {
        return $this->belongsTo(related: Proveedor::class);
    }
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    // Una transacción tiene muchos detalles (productos)
    public function details()
    {
        return $this->hasMany(ProveedorTransacionDetalle::class, 'transaction_id');
    }
}
