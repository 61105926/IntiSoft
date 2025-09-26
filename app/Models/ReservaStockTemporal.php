<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ReservaStockTemporal extends Model
{
    use HasFactory;

    protected $table = 'reservas_stock_temporal';

    protected $fillable = [
        'reserva_id',
        'producto_id',
        'sucursal_id',
        'cantidad_reservada',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'fecha_liberacion',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_liberacion' => 'datetime',
    ];

    // Relaciones
    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
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
    public function scopeActivas($query)
    {
        return $query->where('estado', 'ACTIVA');
    }

    public function scopeVencidas($query)
    {
        return $query->where('estado', 'VENCIDA');
    }

    public function scopeEnFecha($query, $fechaInicio, $fechaFin)
    {
        return $query->where(function($q) use ($fechaInicio, $fechaFin) {
            $q->where(function($subQ) use ($fechaInicio, $fechaFin) {
                // Reserva que empieza dentro del período consultado
                $subQ->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
            })->orWhere(function($subQ) use ($fechaInicio, $fechaFin) {
                // Reserva que termina dentro del período consultado
                $subQ->whereBetween('fecha_fin', [$fechaInicio, $fechaFin]);
            })->orWhere(function($subQ) use ($fechaInicio, $fechaFin) {
                // Reserva que abarca todo el período consultado
                $subQ->where('fecha_inicio', '<=', $fechaInicio)
                    ->where('fecha_fin', '>=', $fechaFin);
            });
        });
    }

    // Métodos de utilidad
    public function estaVencida()
    {
        return $this->fecha_fin < Carbon::now()->toDateString() && $this->estado === 'ACTIVA';
    }

    public function puedeSerLiberada()
    {
        return in_array($this->estado, ['ACTIVA', 'VENCIDA']);
    }

    public function liberar($observaciones = null)
    {
        $this->update([
            'estado' => 'LIBERADA',
            'fecha_liberacion' => now(),
            'observaciones' => $observaciones
        ]);

        // Actualizar stock disponible
        $this->actualizarStockDisponible();

        return $this;
    }

    public function confirmar()
    {
        $this->update(['estado' => 'CONFIRMADA']);
        return $this;
    }

    private function actualizarStockDisponible()
    {
        // Devolver la cantidad al stock disponible si fue liberada
        if ($this->estado === 'LIBERADA') {
            $stock = StockPorSucursal::where('producto_id', $this->producto_id)
                                    ->where('sucursal_id', $this->sucursal_id)
                                    ->first();

            if ($stock) {
                $stock->increment('cantidad_disponible', $this->cantidad_reservada);
            }
        }
    }

    // Métodos estáticos de utilidad
    public static function verificarDisponibilidad($productoId, $sucursalId, $cantidad, $fechaInicio, $fechaFin, $excluirReservaId = null)
    {
        // Obtener stock total en sucursal
        $stock = StockPorSucursal::where('producto_id', $productoId)
                                ->where('sucursal_id', $sucursalId)
                                ->first();

        if (!$stock || $stock->cantidad_disponible < $cantidad) {
            return false;
        }

        // Verificar reservas activas en el período
        $reservasConflicto = self::where('producto_id', $productoId)
                                ->where('sucursal_id', $sucursalId)
                                ->activas()
                                ->enFecha($fechaInicio, $fechaFin);

        if ($excluirReservaId) {
            $reservasConflicto->where('reserva_id', '!=', $excluirReservaId);
        }

        $cantidadReservada = $reservasConflicto->sum('cantidad_reservada');
        $cantidadDisponible = $stock->cantidad_disponible - $cantidadReservada;

        return $cantidadDisponible >= $cantidad;
    }

    public static function reservarStock($reservaId, $productoId, $sucursalId, $cantidad, $fechaInicio, $fechaFin, $observaciones = null)
    {
        // Verificar disponibilidad
        if (!self::verificarDisponibilidad($productoId, $sucursalId, $cantidad, $fechaInicio, $fechaFin)) {
            throw new \Exception('No hay suficiente stock disponible para las fechas solicitadas.');
        }

        // Crear reserva temporal
        return self::create([
            'reserva_id' => $reservaId,
            'producto_id' => $productoId,
            'sucursal_id' => $sucursalId,
            'cantidad_reservada' => $cantidad,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado' => 'ACTIVA',
            'observaciones' => $observaciones
        ]);
    }

    public static function liberarStockVencido()
    {
        $reservasVencidas = self::where('estado', 'ACTIVA')
                               ->where('fecha_fin', '<', Carbon::now()->toDateString())
                               ->get();

        foreach ($reservasVencidas as $reserva) {
            $reserva->update(['estado' => 'VENCIDA']);
        }

        return $reservasVencidas->count();
    }
}