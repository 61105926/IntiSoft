<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoVestimenta extends Model
{
    use HasFactory;

    protected $table = 'evento_vestimentas';

    protected $fillable = [
        'evento_id',
        'participante_id',
        'producto_id',
        'sucursal_id',
        'cantidad',
        'fecha_asignacion',
        'fecha_entrega',
        'fecha_devolucion',
        'estado_vestimenta',
        'observaciones_entrega',
        'observaciones_devolucion',
    ];

    protected $casts = [
        'fecha_asignacion' => 'datetime',
        'fecha_entrega' => 'datetime',
        'fecha_devolucion' => 'datetime',
    ];

    // Estados de vestimenta
    const ESTADO_ASIGNADA = 'ASIGNADA';
    const ESTADO_ENTREGADA = 'ENTREGADA';
    const ESTADO_DEVUELTA = 'DEVUELTA';
    const ESTADO_PERDIDA = 'PERDIDA';
    const ESTADO_DAÑADA = 'DAÑADA';

    // Relaciones
    public function evento()
    {
        return $this->belongsTo(EventoFolklorico::class, 'evento_id');
    }

    public function participante()
    {
        return $this->belongsTo(EventoParticipante::class, 'participante_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    // Scopes
    public function scopeEntregadas($query)
    {
        return $query->where('estado_vestimenta', self::ESTADO_ENTREGADA);
    }

    public function scopeDevueltas($query)
    {
        return $query->where('estado_vestimenta', self::ESTADO_DEVUELTA);
    }

    public function scopePendienteDevolucion($query)
    {
        return $query->where('estado_vestimenta', self::ESTADO_ENTREGADA);
    }

    public function scopePorEvento($query, $eventoId)
    {
        return $query->where('evento_id', $eventoId);
    }

    // Métodos de negocio
    public function puedeEntregarse()
    {
        return $this->estado_vestimenta === self::ESTADO_ASIGNADA;
    }

    public function puedeDevolverse()
    {
        return $this->estado_vestimenta === self::ESTADO_ENTREGADA;
    }

    public function entregarVestimenta($observaciones = null)
    {
        $this->update([
            'estado_vestimenta' => self::ESTADO_ENTREGADA,
            'fecha_entrega' => now(),
            'observaciones_entrega' => $observaciones
        ]);

        // Actualizar stock en el sistema
        $this->actualizarStockEntrega();

        // Registrar en historial
        $this->registrarMovimientoHistorial('SALIDA', 'EVENTO');
    }

    public function devolverVestimenta($observaciones = null)
    {
        $this->update([
            'estado_vestimenta' => self::ESTADO_DEVUELTA,
            'fecha_devolucion' => now(),
            'observaciones_devolucion' => $observaciones
        ]);

        // Actualizar stock en el sistema
        $this->actualizarStockDevolucion();

        // Registrar en historial
        $this->registrarMovimientoHistorial('ENTRADA', 'EVENTO');
    }

    public function marcarComoPerdida($observaciones = null)
    {
        $this->update([
            'estado_vestimenta' => self::ESTADO_PERDIDA,
            'observaciones_devolucion' => $observaciones
        ]);

        // El stock no se recupera en caso de pérdida
        // Registrar en historial como pérdida
        $this->registrarMovimientoHistorial('SALIDA', 'EVENTO', 'Vestimenta perdida en evento');
    }

    public function marcarComoDañada($observaciones = null)
    {
        $this->update([
            'estado_vestimenta' => self::ESTADO_DAÑADA,
            'observaciones_devolucion' => $observaciones
        ]);

        // Mover a stock de mantenimiento
        $this->actualizarStockMantenimiento();

        // Registrar en historial
        $this->registrarMovimientoHistorial('ENTRADA', 'MANTENIMIENTO', 'Vestimenta dañada, requiere mantenimiento');
    }

    private function actualizarStockEntrega()
    {
        $stock = StockPorSucursal::where('producto_id', $this->producto_id)
                                ->where('sucursal_id', $this->sucursal_id)
                                ->first();

        if ($stock) {
            $stock->decrement('stock_disponible', $this->cantidad);
            $stock->increment('stock_en_eventos', $this->cantidad);
        }
    }

    private function actualizarStockDevolucion()
    {
        $stock = StockPorSucursal::where('producto_id', $this->producto_id)
                                ->where('sucursal_id', $this->sucursal_id)
                                ->first();

        if ($stock) {
            $stock->increment('stock_disponible', $this->cantidad);
            $stock->decrement('stock_en_eventos', $this->cantidad);
        }
    }

    private function actualizarStockMantenimiento()
    {
        $stock = StockPorSucursal::where('producto_id', $this->producto_id)
                                ->where('sucursal_id', $this->sucursal_id)
                                ->first();

        if ($stock) {
            $stock->decrement('stock_en_eventos', $this->cantidad);
            $stock->increment('stock_mantenimiento', $this->cantidad);
        }
    }

    private function registrarMovimientoHistorial($tipoMovimiento, $referenciaTipo, $observaciones = null)
    {
        $stock = StockPorSucursal::where('producto_id', $this->producto_id)
                                ->where('sucursal_id', $this->sucursal_id)
                                ->first();

        if ($stock) {
            HistorialProducto::create([
                'producto_id' => $this->producto_id,
                'tipo_movimiento' => $tipoMovimiento,
                'referencia_tipo' => $referenciaTipo,
                'referencia_id' => $this->evento_id,
                'sucursal_id' => $this->sucursal_id,
                'cantidad_anterior' => $stock->stock_total,
                'cantidad_movimiento' => $this->cantidad,
                'cantidad_posterior' => $stock->stock_total,
                'usuario_id' => auth()->id() ?? 1,
                'observaciones' => $observaciones ?? "Movimiento de evento {$this->evento->numero_evento} - Participante {$this->participante->numero_participante}"
            ]);
        }
    }

    public function obtenerTrazabilidad()
    {
        return [
            'evento' => $this->evento->numero_evento,
            'participante' => $this->participante->numero_participante,
            'producto' => $this->producto->nombre,
            'fecha_asignacion' => $this->fecha_asignacion,
            'fecha_entrega' => $this->fecha_entrega,
            'fecha_devolucion' => $this->fecha_devolucion,
            'estado_actual' => $this->estado_vestimenta,
            'sucursal' => $this->sucursal->nombre,
            'observaciones_entrega' => $this->observaciones_entrega,
            'observaciones_devolucion' => $this->observaciones_devolucion,
        ];
    }
}