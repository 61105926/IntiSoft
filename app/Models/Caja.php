<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Caja extends Model
{
    use HasFactory;

    protected $table = 'cajas';

    // Estados de caja
    const ESTADO_ABIERTA = 'ABIERTA';
    const ESTADO_CERRADA = 'CERRADA';

    protected $fillable = [
        'nombre',
        'descripcion',
        'sucursal_id',
        'estado',
        'saldo_inicial',
        'saldo_actual',
        'fecha_apertura',
        'fecha_cierre',
        'usuario_apertura',
        'usuario_cierre',
        'observaciones_apertura',
        'observaciones_cierre',
        'arqueo_sistema',
        'arqueo_fisico',
        'diferencia_arqueo',
        'es_caja_principal'
    ];

    protected $casts = [
        'fecha_apertura' => 'datetime',
        'fecha_cierre' => 'datetime',
        'saldo_inicial' => 'decimal:2',
        'saldo_actual' => 'decimal:2',
        'arqueo_sistema' => 'decimal:2',
        'arqueo_fisico' => 'decimal:2',
        'diferencia_arqueo' => 'decimal:2',
        'es_caja_principal' => 'boolean'
    ];

    // Relaciones
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function usuarioApertura()
    {
        return $this->belongsTo(User::class, 'usuario_apertura');
    }

    public function usuarioCierre()
    {
        return $this->belongsTo(User::class, 'usuario_cierre');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoCaja::class);
    }

    public function movimientosHoy()
    {
        return $this->movimientos()
            ->whereDate('fecha_movimiento', Carbon::today());
    }

    public function movimientosPorPeriodo($fechaInicio, $fechaFin)
    {
        return $this->movimientos()
            ->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin]);
    }

    // Scopes
    public function scopeAbiertas($query)
    {
        return $query->where('estado', self::ESTADO_ABIERTA);
    }

    public function scopeCerradas($query)
    {
        return $query->where('estado', self::ESTADO_CERRADA);
    }

    public function scopePorSucursal($query, $sucursalId)
    {
        return $query->where('sucursal_id', $sucursalId);
    }

    public function scopePrincipal($query)
    {
        return $query->where('es_caja_principal', true);
    }

    // Accessors
    public function getEstadoDisplayAttribute()
    {
        return match($this->estado) {
            self::ESTADO_ABIERTA => 'Abierta',
            self::ESTADO_CERRADA => 'Cerrada',
            default => $this->estado
        };
    }

    public function getEstadoBadgeClassAttribute()
    {
        return match($this->estado) {
            self::ESTADO_ABIERTA => 'bg-success',
            self::ESTADO_CERRADA => 'bg-secondary',
            default => 'bg-warning'
        };
    }

    public function getTotalIngresosHoyAttribute()
    {
        return $this->movimientosHoy()
            ->where('tipo', MovimientoCaja::TIPO_INGRESO)
            ->sum('monto');
    }

    public function getTotalEgresosHoyAttribute()
    {
        return $this->movimientosHoy()
            ->where('tipo', MovimientoCaja::TIPO_EGRESO)
            ->sum('monto');
    }

    public function getSaldoEsperadoAttribute()
    {
        return $this->saldo_inicial + $this->total_ingresos_hoy - $this->total_egresos_hoy;
    }

    public function getEstaAbiertaAttribute()
    {
        return $this->estado === self::ESTADO_ABIERTA;
    }

    public function getTiempoAbiertoAttribute()
    {
        if (!$this->esta_abierta || !$this->fecha_apertura) {
            return null;
        }

        return $this->fecha_apertura->diffForHumans();
    }

    // Métodos de negocio
    public function puedeAbrirse()
    {
        return $this->estado === self::ESTADO_CERRADA;
    }

    public function puedeCerrarse()
    {
        return $this->estado === self::ESTADO_ABIERTA;
    }

    public function abrir($saldoInicial, $observaciones = null, $usuarioId = null)
    {
        if (!$this->puedeAbrirse()) {
            throw new \Exception('La caja ya está abierta o no puede abrirse en su estado actual.');
        }

        $this->update([
            'estado' => self::ESTADO_ABIERTA,
            'saldo_inicial' => $saldoInicial,
            'saldo_actual' => $saldoInicial,
            'fecha_apertura' => now(),
            'usuario_apertura' => $usuarioId ?? auth()->id(),
            'observaciones_apertura' => $observaciones,
            'fecha_cierre' => null,
            'usuario_cierre' => null,
            'observaciones_cierre' => null,
            'arqueo_sistema' => null,
            'arqueo_fisico' => null,
            'diferencia_arqueo' => null,
        ]);

        // Registrar movimiento de apertura
        $this->registrarMovimiento(
            MovimientoCaja::TIPO_INGRESO,
            $saldoInicial,
            'Apertura de caja',
            'APERTURA',
            $usuarioId ?? auth()->id(),
            $observaciones
        );

        return $this;
    }

    public function cerrar($arqueoFisico = null, $observaciones = null, $usuarioId = null)
    {
        if (!$this->puedeCerrarse()) {
            throw new \Exception('La caja no está abierta o no puede cerrarse en su estado actual.');
        }

        $arqueoSistema = $this->calcularArqueoSistema();
        $diferencia = $arqueoFisico ? ($arqueoFisico - $arqueoSistema) : 0;

        $this->update([
            'estado' => self::ESTADO_CERRADA,
            'fecha_cierre' => now(),
            'usuario_cierre' => $usuarioId ?? auth()->id(),
            'observaciones_cierre' => $observaciones,
            'arqueo_sistema' => $arqueoSistema,
            'arqueo_fisico' => $arqueoFisico,
            'diferencia_arqueo' => $diferencia,
        ]);

        // Si hay diferencia, registrar movimiento de ajuste
        if ($diferencia != 0) {
            $this->registrarMovimiento(
                $diferencia > 0 ? MovimientoCaja::TIPO_INGRESO : MovimientoCaja::TIPO_EGRESO,
                abs($diferencia),
                $diferencia > 0 ? 'Sobrante en arqueo' : 'Faltante en arqueo',
                'ARQUEO',
                null,
                "Diferencia de arqueo: Bs. " . number_format($diferencia, 2)
            );
        }

        return $this;
    }

    public function registrarMovimiento($tipo, $monto, $concepto, $categoria = 'VARIOS', $usuarioId = null, $observaciones = null)
    {
        if (!$this->esta_abierta && $categoria !== 'APERTURA') {
            throw new \Exception('No se pueden registrar movimientos en una caja cerrada.');
        }

        $movimiento = $this->movimientos()->create([
            'tipo' => $tipo,
            'monto' => $monto,
            'concepto' => $concepto,
            'categoria' => $categoria,
            'referencia' => null,
            'observaciones' => $observaciones,
            'fecha_movimiento' => now(),
            'usuario_registro' => $usuarioId ?? auth()->id(),
            'saldo_anterior' => $this->saldo_actual,
            'saldo_posterior' => $this->calcularNuevoSaldo($tipo, $monto),
        ]);

        // Actualizar saldo actual
        $this->actualizarSaldo($tipo, $monto);

        return $movimiento;
    }

    private function calcularNuevoSaldo($tipo, $monto)
    {
        if ($tipo === MovimientoCaja::TIPO_INGRESO) {
            return $this->saldo_actual + $monto;
        } else {
            return $this->saldo_actual - $monto;
        }
    }

    private function actualizarSaldo($tipo, $monto)
    {
        if ($tipo === MovimientoCaja::TIPO_INGRESO) {
            $this->increment('saldo_actual', $monto);
        } else {
            $this->decrement('saldo_actual', $monto);
        }
    }

    public function calcularArqueoSistema()
    {
        if (!$this->fecha_apertura) {
            return $this->saldo_inicial;
        }

        $ingresos = $this->movimientos()
            ->where('tipo', MovimientoCaja::TIPO_INGRESO)
            ->where('fecha_movimiento', '>=', $this->fecha_apertura)
            ->sum('monto');

        $egresos = $this->movimientos()
            ->where('tipo', MovimientoCaja::TIPO_EGRESO)
            ->where('fecha_movimiento', '>=', $this->fecha_apertura)
            ->sum('monto');

        return $this->saldo_inicial + $ingresos - $egresos;
    }

    public function obtenerResumenDia()
    {
        $movimientosHoy = $this->movimientosHoy();

        return [
            'ingresos_total' => $movimientosHoy->where('tipo', MovimientoCaja::TIPO_INGRESO)->sum('monto'),
            'egresos_total' => $movimientosHoy->where('tipo', MovimientoCaja::TIPO_EGRESO)->sum('monto'),
            'cantidad_ingresos' => $movimientosHoy->where('tipo', MovimientoCaja::TIPO_INGRESO)->count(),
            'cantidad_egresos' => $movimientosHoy->where('tipo', MovimientoCaja::TIPO_EGRESO)->count(),
            'saldo_inicial' => $this->saldo_inicial,
            'saldo_actual' => $this->saldo_actual,
            'arqueo_sistema' => $this->calcularArqueoSistema(),
        ];
    }

    // Validaciones
    public function puedeRegistrarMovimiento($monto, $tipo)
    {
        if (!$this->esta_abierta) {
            return [false, 'La caja debe estar abierta para registrar movimientos.'];
        }

        if ($monto <= 0) {
            return [false, 'El monto debe ser mayor a cero.'];
        }

        if ($tipo === MovimientoCaja::TIPO_EGRESO && $this->saldo_actual < $monto) {
            return [false, 'No hay suficiente saldo en caja para este egreso.'];
        }

        return [true, null];
    }

    // Método estático para obtener caja principal de una sucursal
    public static function cajaPrincipalDeSucursal($sucursalId)
    {
        return static::where('sucursal_id', $sucursalId)
            ->where('es_caja_principal', true)
            ->first();
    }
}