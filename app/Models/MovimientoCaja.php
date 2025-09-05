<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class MovimientoCaja extends Model
{
    use HasFactory;

    protected $table = 'movimientos_caja';

    // Tipos de movimiento
    const TIPO_INGRESO = 'INGRESO';
    const TIPO_EGRESO = 'EGRESO';

    // Categorías de movimiento
    const CATEGORIA_VENTA = 'VENTA';
    const CATEGORIA_ALQUILER = 'ALQUILER';
    const CATEGORIA_PAGO_ALQUILER = 'PAGO_ALQUILER';
    const CATEGORIA_GARANTIA = 'GARANTIA';
    const CATEGORIA_DEVOLUCION_GARANTIA = 'DEVOLUCION_GARANTIA';
    const CATEGORIA_GASTO_OPERATIVO = 'GASTO_OPERATIVO';
    const CATEGORIA_PAGO_PROVEEDOR = 'PAGO_PROVEEDOR';
    const CATEGORIA_PAGO_SERVICIOS = 'PAGO_SERVICIOS';
    const CATEGORIA_PAGO_SUELDOS = 'PAGO_SUELDOS';
    const CATEGORIA_APERTURA = 'APERTURA';
    const CATEGORIA_ARQUEO = 'ARQUEO';
    const CATEGORIA_TRANSFERENCIA = 'TRANSFERENCIA';
    const CATEGORIA_VARIOS = 'VARIOS';

    protected $fillable = [
        'caja_id',
        'tipo',
        'monto',
        'concepto',
        'categoria',
        'referencia',
        'observaciones',
        'fecha_movimiento',
        'usuario_registro',
        'saldo_anterior',
        'saldo_posterior',
        'alquiler_id',
        'venta_id',
        'garantia_id',
        'metodo_pago',
        'documento_respaldo'
    ];

    protected $casts = [
        'fecha_movimiento' => 'datetime',
        'monto' => 'decimal:2',
        'saldo_anterior' => 'decimal:2',
        'saldo_posterior' => 'decimal:2'
    ];

    // Relaciones
    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(User::class, 'usuario_registro');
    }

    public function alquiler()
    {
        return $this->belongsTo(Alquiler::class);
    }

    public function garantia()
    {
        return $this->belongsTo(Garantia::class);
    }

    // Scopes
    public function scopeIngresos($query)
    {
        return $query->where('tipo', self::TIPO_INGRESO);
    }

    public function scopeEgresos($query)
    {
        return $query->where('tipo', self::TIPO_EGRESO);
    }

    public function scopeHoy($query)
    {
        return $query->whereDate('fecha_movimiento', Carbon::today());
    }

    // Accessors
    public function getTipoDisplayAttribute()
    {
        return match($this->tipo) {
            self::TIPO_INGRESO => 'Ingreso',
            self::TIPO_EGRESO => 'Egreso',
            default => $this->tipo
        };
    }

    public function getTipoBadgeClassAttribute()
    {
        return match($this->tipo) {
            self::TIPO_INGRESO => 'bg-success',
            self::TIPO_EGRESO => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    public function getCategoriaDisplayAttribute()
    {
        return match($this->categoria) {
            self::CATEGORIA_VENTA => 'Venta',
            self::CATEGORIA_ALQUILER => 'Alquiler',
            self::CATEGORIA_PAGO_ALQUILER => 'Pago de Alquiler',
            self::CATEGORIA_GARANTIA => 'Garantía',
            self::CATEGORIA_DEVOLUCION_GARANTIA => 'Devolución de Garantía',
            self::CATEGORIA_GASTO_OPERATIVO => 'Gasto Operativo',
            self::CATEGORIA_PAGO_PROVEEDOR => 'Pago a Proveedor',
            self::CATEGORIA_PAGO_SERVICIOS => 'Pago de Servicios',
            self::CATEGORIA_PAGO_SUELDOS => 'Pago de Sueldos',
            self::CATEGORIA_APERTURA => 'Apertura de Caja',
            self::CATEGORIA_ARQUEO => 'Arqueo de Caja',
            self::CATEGORIA_TRANSFERENCIA => 'Transferencia',
            self::CATEGORIA_VARIOS => 'Varios',
            default => $this->categoria
        };
    }

    // Métodos estáticos
    public static function resumenDiario($cajaId, $fecha = null)
    {
        $fecha = $fecha ?? Carbon::today();

        return self::where('caja_id', $cajaId)
            ->whereDate('fecha_movimiento', $fecha)
            ->selectRaw('tipo, SUM(monto) as total, COUNT(*) as cantidad')
            ->groupBy('tipo')
            ->get();
    }

    public static function obtenerCategoriasDisponibles()
    {
        return [
            self::CATEGORIA_VENTA => 'Venta',
            self::CATEGORIA_ALQUILER => 'Alquiler',
            self::CATEGORIA_PAGO_ALQUILER => 'Pago de Alquiler',
            self::CATEGORIA_GARANTIA => 'Garantía',
            self::CATEGORIA_DEVOLUCION_GARANTIA => 'Devolución de Garantía',
            self::CATEGORIA_GASTO_OPERATIVO => 'Gasto Operativo',
            self::CATEGORIA_PAGO_PROVEEDOR => 'Pago a Proveedor',
            self::CATEGORIA_PAGO_SERVICIOS => 'Pago de Servicios',
            self::CATEGORIA_PAGO_SUELDOS => 'Pago de Sueldos',
            self::CATEGORIA_TRANSFERENCIA => 'Transferencia',
            self::CATEGORIA_VARIOS => 'Varios',
        ];
    }
}