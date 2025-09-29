<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoComponente extends Model
{
    use HasFactory;

    protected $table = 'tipos_componente';

    protected $fillable = [
        'nombre',
        'descripcion',
        'codigo',
        'icono',
        'es_obligatorio_defecto',
        'orden_visualizacion',
        'activo',
    ];

    protected $casts = [
        'es_obligatorio_defecto' => 'boolean',
        'orden_visualizacion' => 'integer',
        'activo' => 'boolean',
    ];

    public function componentes()
    {
        return $this->hasMany(Componente::class);
    }
}
