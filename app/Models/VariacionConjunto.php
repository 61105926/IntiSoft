<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariacionConjunto extends Model
{
    use HasFactory;

    protected $table = 'variaciones_conjunto';

    protected $fillable = [
        'conjunto_id',
        'codigo_variacion',
        'nombre_variacion',
        'talla',
        'color',
        'estilo',
        'material',
        'precio_venta',
        'precio_alquiler_dia',
        'precio_alquiler_semana',
        'precio_alquiler_mes',
        'peso',
        'imagen',
        'observaciones_variacion',
        'activa',
    ];

    protected $casts = [
        'precio_venta' => 'decimal:2',
        'precio_alquiler_dia' => 'decimal:2',
        'precio_alquiler_semana' => 'decimal:2',
        'precio_alquiler_mes' => 'decimal:2',
        'peso' => 'decimal:2',
        'activa' => 'boolean',
    ];

    public function conjunto()
    {
        return $this->belongsTo(Conjunto::class);
    }

    public function instancias()
    {
        return $this->hasMany(InstanciaConjunto::class);
    }
}
