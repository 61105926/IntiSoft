<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conjunto extends Model
{
    use HasFactory;

    protected $fillable = [
        'categoria_conjunto_id',
        'codigo',
        'nombre',
        'descripcion',
        'imagen_principal',
        'precio_venta_base',
        'precio_alquiler_dia',
        'precio_alquiler_semana',
        'precio_alquiler_mes',
        'genero',
        'edad_minima',
        'edad_maxima',
        'temporada',
        'disponible_venta',
        'disponible_alquiler',
        'requiere_limpieza',
        'tiempo_limpieza_horas',
        'peso_aproximado',
        'observaciones',
        'usuario_creacion',
        'activo',
    ];

    protected $casts = [
        'precio_venta_base' => 'decimal:2',
        'precio_alquiler_dia' => 'decimal:2',
        'precio_alquiler_semana' => 'decimal:2',
        'precio_alquiler_mes' => 'decimal:2',
        'edad_minima' => 'integer',
        'edad_maxima' => 'integer',
        'disponible_venta' => 'boolean',
        'disponible_alquiler' => 'boolean',
        'requiere_limpieza' => 'boolean',
        'tiempo_limpieza_horas' => 'integer',
        'peso_aproximado' => 'decimal:2',
        'activo' => 'boolean',
    ];

    // Relaciones
    public function categoriaConjunto()
    {
        return $this->belongsTo(CategoriaConjunto::class);
    }

    public function variaciones()
    {
        return $this->hasMany(VariacionConjunto::class);
    }

    public function componentes()
    {
        return $this->belongsToMany(Componente::class, 'conjunto_componentes')
                    ->withPivot('cantidad_requerida', 'es_obligatorio', 'es_intercambiable', 'orden_ensamblaje')
                    ->withTimestamps();
    }

    public function usuarioCreacion()
    {
        return $this->belongsTo(User::class, 'usuario_creacion');
    }

    // Métodos de negocio
    public function getTotalInstanciasAttribute()
    {
        return $this->variaciones->sum(function ($variacion) {
            return $variacion->instancias->count();
        });
    }

    public function getDisponiblesAttribute()
    {
        return $this->variaciones->sum(function ($variacion) {
            return $variacion->instancias->where('estado_disponibilidad', 'DISPONIBLE')->count();
        });
    }

    public function getRoiAttribute()
    {
        // Placeholder para cálculo de ROI
        return rand(50, 150) / 10;
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorCategoria($query, $categoriaId)
    {
        return $query->where('categoria_conjunto_id', $categoriaId);
    }

    public function scopePorGenero($query, $genero)
    {
        return $query->where('genero', $genero);
    }
}
