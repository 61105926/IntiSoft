<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlquilerDetalleComponente extends Model
{
    use HasFactory;

    protected $table = 'alquiler_detalle_componentes';

    protected $fillable = [
        'alquiler_detalle_id',
        'instancia_componente_id',
        'componente_id',
        'estado_devolucion',
        'fecha_devolucion',
        'estado_fisico_devolucion',
        'observaciones_devolucion',
        'costo_penalizacion',
        'usuario_registro_devolucion'
    ];

    protected $casts = [
        'fecha_devolucion' => 'datetime',
        'costo_penalizacion' => 'decimal:2'
    ];

    // Relaciones
    public function alquilerDetalle()
    {
        return $this->belongsTo(AlquilerDetalle::class);
    }

    public function instanciaComponente()
    {
        return $this->belongsTo(InstanciaComponente::class);
    }

    public function componente()
    {
        return $this->belongsTo(Componente::class);
    }

    public function usuarioRegistroDevolucion()
    {
        return $this->belongsTo(User::class, 'usuario_registro_devolucion');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado_devolucion', 'PENDIENTE');
    }

    public function scopeDevueltos($query)
    {
        return $query->where('estado_devolucion', 'DEVUELTO');
    }

    public function scopePerdidos($query)
    {
        return $query->where('estado_devolucion', 'PERDIDO');
    }

    public function scopeDanados($query)
    {
        return $query->where('estado_devolucion', 'DANADO');
    }

    // Métodos auxiliares
    public function marcarComoDevuelto($estadoFisico = 'BUENO', $observaciones = null)
    {
        $this->update([
            'estado_devolucion' => 'DEVUELTO',
            'fecha_devolucion' => now(),
            'estado_fisico_devolucion' => $estadoFisico,
            'observaciones_devolucion' => $observaciones,
            'usuario_registro_devolucion' => auth()->id()
        ]);
    }

    public function marcarComoPerdido($costoReposicion = 0, $observaciones = null)
    {
        $this->update([
            'estado_devolucion' => 'PERDIDO',
            'fecha_devolucion' => now(),
            'costo_penalizacion' => $costoReposicion,
            'observaciones_devolucion' => $observaciones,
            'usuario_registro_devolucion' => auth()->id()
        ]);
    }

    public function marcarComoDanado($costoPenalizacion = 0, $estadoFisico = 'MALO', $observaciones = null)
    {
        $this->update([
            'estado_devolucion' => 'DANADO',
            'fecha_devolucion' => now(),
            'estado_fisico_devolucion' => $estadoFisico,
            'costo_penalizacion' => $costoPenalizacion,
            'observaciones_devolucion' => $observaciones,
            'usuario_registro_devolucion' => auth()->id()
        ]);
    }
}
