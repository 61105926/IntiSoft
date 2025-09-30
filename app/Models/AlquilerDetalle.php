<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlquilerDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'alquiler_id',
        'producto_id',
        'conjunto_id',
        'instancia_conjunto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'estado_devolucion',
        'observaciones_devolucion',
        'fecha_devolucion',
        'costo_daño',
        'penalizacion_retraso',
        'penalizacion_daños',
        'penalizacion_perdida',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'costo_daño' => 'decimal:2',
        'fecha_devolucion' => 'datetime',
    ];

    // Relaciones
    public function alquiler()
    {
        return $this->belongsTo(Alquiler::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function conjunto()
    {
        return $this->belongsTo(Conjunto::class);
    }

    public function instanciaConjunto()
    {
        return $this->belongsTo(InstanciaConjunto::class, 'instancia_conjunto_id');
    }

    public function componentesAlquilados()
    {
        return $this->hasMany(AlquilerDetalleComponente::class);
    }

    public function historialComponentes()
    {
        return $this->hasMany(HistorialComponentesConjunto::class);
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

    public function scopeDañados($query)
    {
        return $query->whereIn('estado_devolucion', ['DAÑADO', 'PERDIDO']);
    }

    // Accessors
    public function getEstadoDevolucionDisplayAttribute()
    {
        $estados = [
            'PENDIENTE' => 'Pendiente',
            'DEVUELTO' => 'Devuelto',
            'DAÑADO' => 'Dañado',
            'PERDIDO' => 'Perdido'
        ];

        return $estados[$this->estado_devolucion] ?? $this->estado_devolucion;
    }

    public function getTieneCostoDañoAttribute()
    {
        return $this->costo_daño > 0;
    }

    // Métodos
    public function marcarComoDevuelto($observaciones = null)
    {
        $this->update([
            'estado_devolucion' => 'DEVUELTO',
            'fecha_devolucion' => now(),
            'observaciones_devolucion' => $observaciones,
        ]);
    }

    public function marcarComoDañado($costoDaño = 0, $observaciones = null)
    {
        $this->update([
            'estado_devolucion' => 'DAÑADO',
            'fecha_devolucion' => now(),
            'costo_daño' => $costoDaño,
            'observaciones_devolucion' => $observaciones,
        ]);
    }
}
