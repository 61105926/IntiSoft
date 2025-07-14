<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
      public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaProducto::class, 'categoria_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'usuario_creacion');
    }

    public function movimientos()
    {
        return $this->hasMany(InventarioMovimiento::class);
    }
}
