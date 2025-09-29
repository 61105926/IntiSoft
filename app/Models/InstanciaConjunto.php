<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstanciaConjunto extends Model
{
    use HasFactory;

    protected $table = 'instancias_conjunto';

    protected $fillable = [
        'variacion_conjunto_id',
        'numero_serie',
        'codigo_interno',
        'lote_fabricacion',
        'sucursal_id',
        'estado_fisico',
        'estado_disponibilidad',
        'fecha_adquisicion',
        'fecha_ultimo_uso',
        'fecha_proxima_disponibilidad',
        'total_usos',
        'total_ingresos',
        'ubicacion_almacen',
        'observaciones',
        'usuario_creacion',
        'activa',
    ];

    protected $casts = [
        'fecha_adquisicion' => 'date',
        'fecha_ultimo_uso' => 'date',
        'fecha_proxima_disponibilidad' => 'date',
        'total_usos' => 'integer',
        'total_ingresos' => 'decimal:2',
        'activa' => 'boolean',
    ];

    public function variacionConjunto()
    {
        return $this->belongsTo(VariacionConjunto::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}
