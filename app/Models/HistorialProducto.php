<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialProducto extends Model
{
    use HasFactory;

    protected $table = 'historial_productos';

    protected $fillable = [
        'producto_id',
        'tipo_movimiento',
        'referencia_tipo',
        'referencia_id',
        'sucursal_id',
        'cantidad_anterior',
        'cantidad_movimiento',
        'cantidad_posterior',
        'precio_unitario',
        'usuario_id',
        'observaciones',
        'fecha_movimiento',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'fecha_movimiento' => 'datetime',
    ];

    // Tipos de movimiento
    const TIPO_ENTRADA = 'ENTRADA';
    const TIPO_SALIDA = 'SALIDA';
    const TIPO_RESERVA = 'RESERVA';
    const TIPO_LIBERACION = 'LIBERACION';
    const TIPO_ALQUILER = 'ALQUILER';
    const TIPO_DEVOLUCION = 'DEVOLUCION';
    const TIPO_VENTA = 'VENTA';
    const TIPO_EVENTO = 'EVENTO';
    const TIPO_MANTENIMIENTO = 'MANTENIMIENTO';
    const TIPO_AJUSTE = 'AJUSTE';

    // Tipos de referencia
    const REF_RESERVA = 'RESERVA';
    const REF_ALQUILER = 'ALQUILER';
    const REF_VENTA = 'VENTA';
    const REF_EVENTO = 'EVENTO';
    const REF_AJUSTE = 'AJUSTE';
    const REF_MANTENIMIENTO = 'MANTENIMIENTO';

    // Relaciones
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePorProducto($query, $productoId)
    {
        return $query->where('producto_id', $productoId);
    }

    public function scopePorSucursal($query, $sucursalId)
    {
        return $query->where('sucursal_id', $sucursalId);
    }

    public function scopePorTipoMovimiento($query, $tipo)
    {
        return $query->where('tipo_movimiento', $tipo);
    }

    public function scopePorReferencia($query, $tipo, $id)
    {
        return $query->where('referencia_tipo', $tipo)
                    ->where('referencia_id', $id);
    }

    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin]);
    }

    // Métodos de negocio
    public function getReferenciaAttribute()
    {
        switch ($this->referencia_tipo) {
            case self::REF_RESERVA:
                return Reserva::find($this->referencia_id);
            case self::REF_ALQUILER:
                return Alquiler::find($this->referencia_id);
            case self::REF_VENTA:
                return Venta::find($this->referencia_id);
            case self::REF_EVENTO:
                return EventoFolklorico::find($this->referencia_id);
            default:
                return null;
        }
    }

    public function getNumeroReferenciaAttribute()
    {
        $referencia = $this->getReferencia();
        if (!$referencia) return 'N/A';

        switch ($this->referencia_tipo) {
            case self::REF_RESERVA:
                return $referencia->numero_reserva ?? 'N/A';
            case self::REF_ALQUILER:
                return $referencia->numero_contrato ?? 'N/A';
            case self::REF_VENTA:
                return $referencia->numero_venta ?? 'N/A';
            case self::REF_EVENTO:
                return $referencia->numero_evento ?? 'N/A';
            default:
                return 'N/A';
        }
    }

    public function getReferencia()
    {
        return $this->referencia;
    }

    public static function registrarMovimiento($data)
    {
        return self::create([
            'producto_id' => $data['producto_id'],
            'tipo_movimiento' => $data['tipo_movimiento'],
            'referencia_tipo' => $data['referencia_tipo'],
            'referencia_id' => $data['referencia_id'],
            'sucursal_id' => $data['sucursal_id'],
            'cantidad_anterior' => $data['cantidad_anterior'],
            'cantidad_movimiento' => $data['cantidad_movimiento'],
            'cantidad_posterior' => $data['cantidad_posterior'],
            'precio_unitario' => $data['precio_unitario'] ?? null,
            'usuario_id' => $data['usuario_id'] ?? auth()->id(),
            'observaciones' => $data['observaciones'] ?? null,
            'fecha_movimiento' => $data['fecha_movimiento'] ?? now(),
        ]);
    }

    public static function obtenerHistorialProducto($productoId, $sucursalId = null, $limite = 50)
    {
        $query = self::where('producto_id', $productoId)
                    ->with(['usuario', 'sucursal'])
                    ->orderBy('fecha_movimiento', 'desc');

        if ($sucursalId) {
            $query->where('sucursal_id', $sucursalId);
        }

        return $query->limit($limite)->get();
    }

    public static function obtenerMovimientosPorPeriodo($fechaInicio, $fechaFin, $sucursalId = null)
    {
        $query = self::with(['producto', 'usuario', 'sucursal'])
                    ->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin])
                    ->orderBy('fecha_movimiento', 'desc');

        if ($sucursalId) {
            $query->where('sucursal_id', $sucursalId);
        }

        return $query->get();
    }

    public static function obtenerEstadisticasMovimientos($productoId, $sucursalId = null)
    {
        $query = self::where('producto_id', $productoId);

        if ($sucursalId) {
            $query->where('sucursal_id', $sucursalId);
        }

        return [
            'total_movimientos' => $query->count(),
            'entradas_totales' => $query->where('tipo_movimiento', self::TIPO_ENTRADA)->sum('cantidad_movimiento'),
            'salidas_totales' => $query->where('tipo_movimiento', self::TIPO_SALIDA)->sum('cantidad_movimiento'),
            'reservas_totales' => $query->where('tipo_movimiento', self::TIPO_RESERVA)->sum('cantidad_movimiento'),
            'alquileres_totales' => $query->where('tipo_movimiento', self::TIPO_ALQUILER)->sum('cantidad_movimiento'),
            'ventas_totales' => $query->where('tipo_movimiento', self::TIPO_VENTA)->sum('cantidad_movimiento'),
            'eventos_totales' => $query->where('tipo_movimiento', self::TIPO_EVENTO)->sum('cantidad_movimiento'),
            'ultimo_movimiento' => $query->orderBy('fecha_movimiento', 'desc')->first()?->fecha_movimiento,
        ];
    }

    public function getDescripcionCompletaAttribute()
    {
        $accion = '';
        switch ($this->tipo_movimiento) {
            case self::TIPO_ENTRADA:
                $accion = 'Ingreso';
                break;
            case self::TIPO_SALIDA:
                $accion = 'Salida';
                break;
            case self::TIPO_RESERVA:
                $accion = 'Reservado';
                break;
            case self::TIPO_LIBERACION:
                $accion = 'Liberado';
                break;
            case self::TIPO_ALQUILER:
                $accion = 'Alquilado';
                break;
            case self::TIPO_DEVOLUCION:
                $accion = 'Devuelto';
                break;
            case self::TIPO_VENTA:
                $accion = 'Vendido';
                break;
            case self::TIPO_EVENTO:
                $accion = 'Evento';
                break;
            case self::TIPO_MANTENIMIENTO:
                $accion = 'Mantenimiento';
                break;
            case self::TIPO_AJUSTE:
                $accion = 'Ajuste';
                break;
        }

        return "{$accion} - {$this->numero_referencia} - {$this->cantidad_movimiento} unidades";
    }
}