<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstanciaComponente extends Model
{
    use HasFactory;

    protected $table = 'instancia_componentes';

    protected $fillable = [
        'instancia_conjunto_id',
        'componente_id',
        'producto_id',
        'numero_serie_componente',
        'estado_fisico',
        'estado_actual',
        'fecha_asignacion',
        'fecha_desvinculacion',
        'observaciones',
        'usuario_asignacion'
    ];

    protected $casts = [
        'fecha_asignacion' => 'datetime',
        'fecha_desvinculacion' => 'datetime'
    ];

    // Relaciones
    public function instanciaConjunto()
    {
        return $this->belongsTo(InstanciaConjunto::class, 'instancia_conjunto_id');
    }

    public function componente()
    {
        return $this->belongsTo(Componente::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function usuarioAsignacion()
    {
        return $this->belongsTo(User::class, 'usuario_asignacion');
    }

    public function alquilerDetalleComponentes()
    {
        return $this->hasMany(AlquilerDetalleComponente::class);
    }

    public function historial()
    {
        return $this->hasMany(HistorialComponentesConjunto::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estado_actual', 'ASIGNADO');
    }

    public function scopePerdidos($query)
    {
        return $query->where('estado_actual', 'PERDIDO');
    }

    public function scopeDanados($query)
    {
        return $query->where('estado_actual', 'DANADO');
    }
}
