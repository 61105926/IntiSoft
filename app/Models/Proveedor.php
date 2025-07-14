<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function productos()
    {
        return $this->hasMany(ProveedorProductos::class, 'proveedor_id'); // Cambia 'ProveedorProducto' al nombre correcto de tu modelo
    }
    public function transactions()
    {
        return $this->hasMany(ProveedorTransacion::class);
    }
}
