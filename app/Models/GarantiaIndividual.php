<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarantiaIndividual extends Model
{
    use HasFactory;

    protected $table = 'garantias_individuales';

    protected $fillable = [
        'alquiler_id',
        'evento_id',
        'producto_id',
        'cliente_id',
        'numero_garantia',
        'cantidad',
        'monto_garantia_unitario',
        'monto_garantia_total',
        'monto_devuelto',
        'estado_prenda',
        'estado_garantia',
        'observaciones_entrega',
        'observaciones_devolucion',
        'evaluacion_estado',
        'costo_reparacion',
        'descuento_aplicado',
        'fecha_entrega',
        'fecha_devolucion',
        'usuario_entrega',
        'usuario_devolucion',
    ];

    protected $casts = [
        'monto_garantia_unitario' => 'decimal:2',
        'monto_garantia_total' => 'decimal:2',
        'monto_devuelto' => 'decimal:2',
        'costo_reparacion' => 'decimal:2',
        'descuento_aplicado' => 'decimal:2',
        'fecha_entrega' => 'datetime',
        'fecha_devolucion' => 'datetime',
        'evaluacion_estado' => 'array',
    ];

    // Relaciones
    public function alquiler()
    {
        return $this->belongsTo(Alquiler::class);
    }

    public function evento()
    {
        return $this->belongsTo(EventoFolklorico::class, 'evento_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function usuarioEntrega()
    {
        return $this->belongsTo(User::class, 'usuario_entrega');
    }

    public function usuarioDevolucion()
    {
        return $this->belongsTo(User::class, 'usuario_devolucion');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('estado_garantia', 'ACTIVA');
    }

    public function scopeDevueltas($query)
    {
        return $query->whereIn('estado_garantia', ['DEVUELTA_COMPLETA', 'DEVUELTA_PARCIAL']);
    }

    public function scopePorCliente($query, $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }

    public function scopePorAlquiler($query, $alquilerId)
    {
        return $query->where('alquiler_id', $alquilerId);
    }

    public function scopePorEvento($query, $eventoId)
    {
        return $query->where('evento_id', $eventoId);
    }

    // Accessors
    public function getMontoRetenidoAttribute()
    {
        return $this->monto_garantia_total - $this->monto_devuelto;
    }

    public function getPorcentajeDevueltoAttribute()
    {
        return $this->monto_garantia_total > 0
            ? ($this->monto_devuelto / $this->monto_garantia_total) * 100
            : 0;
    }

    public function getEsDevolucionCompletaAttribute()
    {
        return $this->monto_devuelto >= $this->monto_garantia_total;
    }

    // Métodos de negocio
    public function puedeSerDevuelta()
    {
        return $this->estado_prenda === 'ENTREGADA' &&
               $this->estado_garantia === 'ACTIVA';
    }

    public function registrarDevolucion($estadoPrenda, $evaluacion, $usuarioDevolucionId, $observaciones = null)
    {
        if (!$this->puedeSerDevuelta()) {
            throw new \Exception('Esta garantía no puede ser procesada para devolución.');
        }

        // Calcular monto a devolver basado en evaluación
        $montoDevolver = $this->calcularMontoDevolucion($estadoPrenda, $evaluacion);

        $this->update([
            'estado_prenda' => $estadoPrenda,
            'estado_garantia' => $this->determinarEstadoGarantia($montoDevolver),
            'evaluacion_estado' => $evaluacion,
            'monto_devuelto' => $montoDevolver,
            'fecha_devolucion' => now(),
            'usuario_devolucion' => $usuarioDevolucionId,
            'observaciones_devolucion' => $observaciones,
            'costo_reparacion' => $evaluacion['costo_reparacion'] ?? 0,
            'descuento_aplicado' => $evaluacion['descuento_aplicado'] ?? 0,
        ]);

        // Registrar movimiento en caja si hay devolución
        if ($montoDevolver > 0) {
            $this->registrarMovimientoCaja($montoDevolver);
        }

        return $this;
    }

    private function calcularMontoDevolucion($estadoPrenda, $evaluacion)
    {
        switch ($estadoPrenda) {
            case 'DEVUELTA':
                // Prenda devuelta en buen estado = devolución completa
                $condicion = $evaluacion['condicion'] ?? 'buena';
                switch ($condicion) {
                    case 'buena':
                        return $this->monto_garantia_total;
                    case 'regular':
                        $descuento = $evaluacion['descuento_aplicado'] ?? ($this->monto_garantia_total * 0.1);
                        return max(0, $this->monto_garantia_total - $descuento);
                    case 'mala':
                        $costoReparacion = $evaluacion['costo_reparacion'] ?? ($this->monto_garantia_total * 0.5);
                        return max(0, $this->monto_garantia_total - $costoReparacion);
                    default:
                        return $this->monto_garantia_total;
                }

            case 'DAÑADA':
                $costoReparacion = $evaluacion['costo_reparacion'] ?? $this->monto_garantia_total;
                return max(0, $this->monto_garantia_total - $costoReparacion);

            case 'PERDIDA':
                return 0; // No se devuelve nada si se perdió la prenda

            default:
                return 0;
        }
    }

    private function determinarEstadoGarantia($montoDevolver)
    {
        if ($montoDevolver >= $this->monto_garantia_total) {
            return 'DEVUELTA_COMPLETA';
        } elseif ($montoDevolver > 0) {
            return 'DEVUELTA_PARCIAL';
        } else {
            return 'RETENIDA';
        }
    }

    private function registrarMovimientoCaja($monto)
    {
        // Buscar caja abierta de la sucursal del alquiler/evento
        $sucursalId = $this->alquiler
            ? $this->alquiler->sucursal_id
            : ($this->evento ? $this->evento->sucursal_id : null);

        if ($sucursalId) {
            $caja = Caja::where('sucursal_id', $sucursalId)
                       ->where('estado', 'ABIERTA')
                       ->first();

            if ($caja) {
                MovimientoCaja::create([
                    'caja_id' => $caja->id,
                    'tipo_movimiento' => 'EGRESO',
                    'categoria' => 'DEVOLUCION_GARANTIA',
                    'concepto' => "Devolución garantía individual - {$this->numero_garantia}",
                    'monto' => $monto,
                    'metodo_pago' => 'EFECTIVO',
                    'referencia_tipo' => $this->alquiler_id ? 'ALQUILER' : 'EVENTO',
                    'referencia_id' => $this->alquiler_id ?: $this->evento_id,
                    'usuario_id' => $this->usuario_devolucion,
                    'observaciones' => "Devolución garantía producto: {$this->producto->nombre}"
                ]);
            }
        }
    }

    // Métodos estáticos
    public static function generarNumeroGarantia($tipo = 'ALQ')
    {
        $año = date('Y');
        $ultimo = self::where('numero_garantia', 'like', "GAR-{$tipo}-{$año}-%")
                     ->orderBy('numero_garantia', 'desc')
                     ->first();

        $siguienteNumero = 1;
        if ($ultimo) {
            $ultimoNumero = intval(substr($ultimo->numero_garantia, -6));
            $siguienteNumero = $ultimoNumero + 1;
        }

        return "GAR-{$tipo}-{$año}-" . str_pad($siguienteNumero, 6, '0', STR_PAD_LEFT);
    }

    public static function crearGarantiaIndividual($datos)
    {
        $tipo = isset($datos['alquiler_id']) ? 'ALQ' : 'EVT';
        $numero = self::generarNumeroGarantia($tipo);

        return self::create(array_merge($datos, [
            'numero_garantia' => $numero,
            'monto_garantia_total' => $datos['monto_garantia_unitario'] * $datos['cantidad'],
            'fecha_entrega' => now(),
            'estado_prenda' => 'ENTREGADA',
            'estado_garantia' => 'ACTIVA',
        ]));
    }
}