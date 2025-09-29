<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConjuntoComponente extends Model
{
    use HasFactory;

    protected $table = 'conjunto_componentes';

    protected $fillable = [
        'conjunto_id',
        'componente_id',
        'cantidad_requerida',
        'es_obligatorio',
        'es_intercambiable',
        'orden_ensamblaje',
        'observaciones',
    ];

    protected $casts = [
        'cantidad_requerida' => 'integer',
        'es_obligatorio' => 'boolean',
        'es_intercambiable' => 'boolean',
        'orden_ensamblaje' => 'integer',
    ];

    public function conjunto()
    {
        return $this->belongsTo(Conjunto::class);
    }

    public function componente()
    {
        return $this->belongsTo(Componente::class);
    }
}
