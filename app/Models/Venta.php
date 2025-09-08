<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_venta',
        'cliente_id',
        'sucursal_id',
        'usuario_id',
        'fecha_venta',
        'fecha_entrega',
        'estado',
        'estado_pago',
        'subtotal',
        'descuento',
        'total',
        'monto_pagado',
        'saldo_pendiente',
        'metodo_pago',
        'observaciones',
        'documento_referencia',
        'impuestos',
        'moneda',
        'tipo_cambio',
        'caja_id'
    ];

    protected $casts = [
        'fecha_venta' => 'datetime',
        'fecha_entrega' => 'datetime',
        'subtotal' => 'decimal:2',
        'descuento' => 'decimal:2',
        'total' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
        'saldo_pendiente' => 'decimal:2',
        'impuestos' => 'decimal:2',
        'tipo_cambio' => 'decimal:4'
    ];

    // Estados disponibles
    const ESTADO_PENDIENTE = 'PENDIENTE';
    const ESTADO_COMPLETADA = 'COMPLETADA';
    const ESTADO_CANCELADA = 'CANCELADA';
    const ESTADO_DEVUELTA = 'DEVUELTA';

    const ESTADO_PAGO_PENDIENTE = 'PENDIENTE';
    const ESTADO_PAGO_PAGADO = 'PAGADO';
    const ESTADO_PAGO_PARCIAL = 'PARCIAL';

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class);
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    public function scopeCompletadas($query)
    {
        return $query->where('estado', self::ESTADO_COMPLETADA);
    }

    public function scopeHoy($query)
    {
        return $query->whereDate('fecha_venta', Carbon::today());
    }

    public function scopeEsteRango($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_venta', [$fechaInicio, $fechaFin]);
    }

    // Accessors
    public function getEstadoDisplayAttribute()
    {
        return match($this->estado) {
            self::ESTADO_PENDIENTE => 'Pendiente',
            self::ESTADO_COMPLETADA => 'Completada',
            self::ESTADO_CANCELADA => 'Cancelada',
            self::ESTADO_DEVUELTA => 'Devuelta',
            default => $this->estado
        };
    }

    public function getEstadoPagoDisplayAttribute()
    {
        return match($this->estado_pago) {
            self::ESTADO_PAGO_PENDIENTE => 'Pendiente',
            self::ESTADO_PAGO_PAGADO => 'Pagado',
            self::ESTADO_PAGO_PARCIAL => 'Parcial',
            default => $this->estado_pago
        };
    }

    public function getEstadoBadgeClassAttribute()
    {
        return match($this->estado) {
            self::ESTADO_PENDIENTE => 'bg-warning',
            self::ESTADO_COMPLETADA => 'bg-success',
            self::ESTADO_CANCELADA => 'bg-danger',
            self::ESTADO_DEVUELTA => 'bg-secondary',
            default => 'bg-secondary'
        };
    }

    public function getEstadoPagoBadgeClassAttribute()
    {
        return match($this->estado_pago) {
            self::ESTADO_PAGO_PENDIENTE => 'bg-danger',
            self::ESTADO_PAGO_PAGADO => 'bg-success',
            self::ESTADO_PAGO_PARCIAL => 'bg-warning',
            default => 'bg-secondary'
        };
    }

    // Métodos de negocio
    public function calcularTotales()
    {
        $subtotal = $this->detalles->sum('subtotal');
        $this->subtotal = $subtotal;
        $this->total = $subtotal - $this->descuento + $this->impuestos;
        $this->saldo_pendiente = $this->total - $this->monto_pagado;
        
        // Actualizar estado de pago
        if ($this->monto_pagado >= $this->total) {
            $this->estado_pago = self::ESTADO_PAGO_PAGADO;
        } elseif ($this->monto_pagado > 0) {
            $this->estado_pago = self::ESTADO_PAGO_PARCIAL;
        } else {
            $this->estado_pago = self::ESTADO_PAGO_PENDIENTE;
        }
    }

    public function generarNumeroVenta()
    {
        $sucursalId = str_pad($this->sucursal_id, 2, '0', STR_PAD_LEFT);
        $fecha = Carbon::parse($this->fecha_venta)->format('Ymd');
        
        // Obtener el último número de venta del día para esta sucursal
        $ultimaVenta = self::where('sucursal_id', $this->sucursal_id)
            ->whereDate('fecha_venta', Carbon::parse($this->fecha_venta))
            ->orderBy('id', 'desc')
            ->first();
        
        $siguiente = 1;
        if ($ultimaVenta) {
            $partes = explode('-', $ultimaVenta->numero_venta);
            if (count($partes) >= 3) {
                $siguiente = intval($partes[2]) + 1;
            }
        }
        
        return "VTA-{$sucursalId}{$fecha}-" . str_pad($siguiente, 4, '0', STR_PAD_LEFT);
    }

    public function procesarPago($monto, $cajaId = null)
    {
        DB::beginTransaction();
        try {
            $this->monto_pagado += $monto;
            $this->calcularTotales();
            
            if ($cajaId && $this->caja_id != $cajaId) {
                $this->caja_id = $cajaId;
            }
            
            $this->save();
            
            // Registrar movimiento en caja si hay una caja asignada
            if ($this->caja_id) {
                $caja = Caja::find($this->caja_id);
                if ($caja && $caja->estado === 'ABIERTA') {
                    $caja->registrarMovimiento(
                        MovimientoCaja::TIPO_INGRESO,
                        $monto,
                        "Pago venta {$this->numero_venta}",
                        MovimientoCaja::CATEGORIA_VENTA,
                        $this->numero_venta,
                        "Pago de venta"
                    );
                }
            }
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function completar()
    {
        $this->estado = self::ESTADO_COMPLETADA;
        $this->save();
        
        // Actualizar stock de productos
        foreach ($this->detalles as $detalle) {
            if ($detalle->estado === 'ACTIVO') {
                $stockSucursal = StockPorSucursal::where('producto_id', $detalle->producto_id)
                    ->where('sucursal_id', $this->sucursal_id)
                    ->first();
                    
                if ($stockSucursal) {
                    $stockSucursal->stock_actual -= $detalle->cantidad;
                    $stockSucursal->save();
                }
            }
        }
    }

    // Métodos estáticos
    public static function obtenerEstadosDisponibles()
    {
        return [
            self::ESTADO_PENDIENTE => 'Pendiente',
            self::ESTADO_COMPLETADA => 'Completada',
            self::ESTADO_CANCELADA => 'Cancelada',
            self::ESTADO_DEVUELTA => 'Devuelta'
        ];
    }

    public static function obtenerEstadosPagoDisponibles()
    {
        return [
            self::ESTADO_PAGO_PENDIENTE => 'Pendiente',
            self::ESTADO_PAGO_PAGADO => 'Pagado',
            self::ESTADO_PAGO_PARCIAL => 'Parcial'
        ];
    }

    public static function resumenVentasHoy($sucursalId = null)
    {
        $query = self::whereDate('fecha_venta', Carbon::today());
        
        if ($sucursalId) {
            $query->where('sucursal_id', $sucursalId);
        }
        
        return [
            'total_ventas' => $query->count(),
            'monto_total' => $query->sum('total'),
            'monto_pagado' => $query->sum('monto_pagado'),
            'pendientes' => $query->where('estado', self::ESTADO_PENDIENTE)->count(),
            'completadas' => $query->where('estado', self::ESTADO_COMPLETADA)->count()
        ];
    }
}
