<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Componente extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_componente_id',
        'codigo',
        'nombre',
        'descripcion',
        'imagen',
        'talla',
        'color',
        'material',
        'genero',
        'peso',
        'costo_unitario',
        'precio_venta_individual',
        'precio_alquiler_individual',
        'es_reutilizable',
        'requiere_limpieza',
        'tiempo_limpieza_horas',
        'vida_util_usos',
        'observaciones',
        'usuario_creacion',
        'activo',
    ];

    protected $casts = [
        'peso' => 'decimal:2',
        'costo_unitario' => 'decimal:2',
        'precio_venta_individual' => 'decimal:2',
        'precio_alquiler_individual' => 'decimal:2',
        'es_reutilizable' => 'boolean',
        'requiere_limpieza' => 'boolean',
        'tiempo_limpieza_horas' => 'integer',
        'vida_util_usos' => 'integer',
        'activo' => 'boolean',
    ];

    public function tipoComponente()
    {
        return $this->belongsTo(TipoComponente::class);
    }

    public function conjuntos()
    {
        return $this->belongsToMany(Conjunto::class, 'conjunto_componentes')
                    ->withPivot('cantidad_requerida', 'es_obligatorio', 'es_intercambiable', 'orden_ensamblaje')
                    ->withTimestamps();
    }
}
