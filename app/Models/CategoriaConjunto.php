<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaConjunto extends Model
{
    use HasFactory;

    protected $table = 'categorias_conjunto';

    protected $fillable = [
        'nombre',
        'descripcion',
        'codigo',
        'icono',
        'orden_visualizacion',
        'activo',
    ];

    protected $casts = [
        'orden_visualizacion' => 'integer',
        'activo' => 'boolean',
    ];

    public function conjuntos()
    {
        return $this->hasMany(Conjunto::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
