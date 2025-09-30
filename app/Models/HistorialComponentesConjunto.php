<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialComponentesConjunto extends Model
{
    use HasFactory;

    protected $table = 'historial_componentes_conjunto';

    public $timestamps = false;

    protected $fillable = [
        'instancia_conjunto_id',
        'componente_id',
        'instancia_componente_id',
        'tipo_movimiento',
        'producto_anterior_id',
        'producto_nuevo_id',
        'alquiler_detalle_id',
        'motivo',
        'costo_reposicion',
        'usuario_registro',
        'fecha_movimiento',
        'created_at'
    ];

    protected $casts = [
        'fecha_movimiento' => 'datetime',
        'costo_reposicion' => 'decimal:2',
        'created_at' => 'datetime'
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

    public function instanciaComponente()
    {
        return $this->belongsTo(InstanciaComponente::class);
    }

    public function productoAnterior()
    {
        return $this->belongsTo(Producto::class, 'producto_anterior_id');
    }

    public function productoNuevo()
    {
        return $this->belongsTo(Producto::class, 'producto_nuevo_id');
    }

    public function alquilerDetalle()
    {
        return $this->belongsTo(AlquilerDetalle::class);
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(User::class, 'usuario_registro');
    }

    // Scopes
    public function scopePerdidas($query)
    {
        return $query->where('tipo_movimiento', 'PERDIDA');
    }

    public function scopeReposiciones($query)
    {
        return $query->where('tipo_movimiento', 'REPOSICION');
    }

    public function scopeReemplazos($query)
    {
        return $query->where('tipo_movimiento', 'REEMPLAZO');
    }

    public function scopeDanos($query)
    {
        return $query->where('tipo_movimiento', 'DANO');
    }

    // Métodos estáticos para crear registros
    public static function registrarAsignacionInicial($instanciaConjuntoId, $componenteId, $instanciaComponenteId, $productoId)
    {
        return self::create([
            'instancia_conjunto_id' => $instanciaConjuntoId,
            'componente_id' => $componenteId,
            'instancia_componente_id' => $instanciaComponenteId,
            'tipo_movimiento' => 'ASIGNACION_INICIAL',
            'producto_nuevo_id' => $productoId,
            'fecha_movimiento' => now(),
            'usuario_registro' => auth()->id(),
            'created_at' => now()
        ]);
    }

    public static function registrarPerdida($instanciaConjuntoId, $componenteId, $instanciaComponenteId, $alquilerDetalleId, $motivo = null)
    {
        return self::create([
            'instancia_conjunto_id' => $instanciaConjuntoId,
            'componente_id' => $componenteId,
            'instancia_componente_id' => $instanciaComponenteId,
            'tipo_movimiento' => 'PERDIDA',
            'alquiler_detalle_id' => $alquilerDetalleId,
            'motivo' => $motivo,
            'fecha_movimiento' => now(),
            'usuario_registro' => auth()->id(),
            'created_at' => now()
        ]);
    }

    public static function registrarReposicion($instanciaConjuntoId, $componenteId, $instanciaComponenteId, $productoNuevoId, $costoReposicion, $motivo = null)
    {
        return self::create([
            'instancia_conjunto_id' => $instanciaConjuntoId,
            'componente_id' => $componenteId,
            'instancia_componente_id' => $instanciaComponenteId,
            'tipo_movimiento' => 'REPOSICION',
            'producto_nuevo_id' => $productoNuevoId,
            'costo_reposicion' => $costoReposicion,
            'motivo' => $motivo,
            'fecha_movimiento' => now(),
            'usuario_registro' => auth()->id(),
            'created_at' => now()
        ]);
    }

    public static function registrarReemplazo($instanciaConjuntoId, $componenteId, $productoAnteriorId, $productoNuevoId, $costoReposicion, $motivo = null)
    {
        return self::create([
            'instancia_conjunto_id' => $instanciaConjuntoId,
            'componente_id' => $componenteId,
            'tipo_movimiento' => 'REEMPLAZO',
            'producto_anterior_id' => $productoAnteriorId,
            'producto_nuevo_id' => $productoNuevoId,
            'costo_reposicion' => $costoReposicion,
            'motivo' => $motivo,
            'fecha_movimiento' => now(),
            'usuario_registro' => auth()->id(),
            'created_at' => now()
        ]);
    }
}
