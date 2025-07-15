<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaProducto extends Model
{
    use HasFactory;
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }
}
