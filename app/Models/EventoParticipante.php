<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoParticipante extends Model
{
    use HasFactory;

    protected $table = 'eventos_participantes';

    protected $fillable = [
        'evento_id',
        'cliente_id',
        'garantia_id',
        'numero_participante',
        'nombre_completo',
        'cedula',
        'telefono',
        'email',
        'edad',
        'talla_general',
        'observaciones_especiales',
        'monto_garantia',
        'monto_participacion',
        'estado_pago',
        'estado_participante',
        'fecha_registro',
    ];

    protected $casts = [
        'monto_garantia' => 'decimal:2',
        'monto_participacion' => 'decimal:2',
        'fecha_registro' => 'datetime',
    ];

    // Estados de pago
    const PAGO_PENDIENTE = 'PENDIENTE';
    const PAGO_PARCIAL = 'PARCIAL';
    const PAGO_PAGADO = 'PAGADO';

    // Estados del participante
    const ESTADO_REGISTRADO = 'REGISTRADO';
    const ESTADO_CONFIRMADO = 'CONFIRMADO';
    const ESTADO_VESTIMENTA_ASIGNADA = 'VESTIMENTA_ASIGNADA';
    const ESTADO_FINALIZADO = 'FINALIZADO';
    const ESTADO_CANCELADO = 'CANCELADO';

    // Tallas
    const TALLA_XS = 'XS';
    const TALLA_S = 'S';
    const TALLA_M = 'M';
    const TALLA_L = 'L';
    const TALLA_XL = 'XL';
    const TALLA_XXL = 'XXL';

    // Relaciones
    public function evento()
    {
        return $this->belongsTo(EventoFolklorico::class, 'evento_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function garantia()
    {
        return $this->belongsTo(Garantia::class);
    }

    public function vestimentas()
    {
        return $this->hasMany(EventoVestimenta::class, 'participante_id');
    }

    // Scopes
    public function scopeConfirmados($query)
    {
        return $query->where('estado_participante', '!=', self::ESTADO_CANCELADO);
    }

    public function scopePagados($query)
    {
        return $query->where('estado_pago', self::PAGO_PAGADO);
    }

    public function scopePorTalla($query, $talla)
    {
        return $query->where('talla_general', $talla);
    }

    // Métodos de negocio
    public function puedeEditarse()
    {
        return in_array($this->estado_participante, [
            self::ESTADO_REGISTRADO,
            self::ESTADO_CONFIRMADO
        ]);
    }

    public function puedeCancelarse()
    {
        return $this->estado_participante !== self::ESTADO_CANCELADO &&
               $this->estado_participante !== self::ESTADO_FINALIZADO;
    }

    public function puedeAsignarVestimenta()
    {
        return $this->estado_participante === self::ESTADO_CONFIRMADO &&
               $this->estado_pago !== self::PAGO_PENDIENTE;
    }

    public function tieneVestimentaCompleta()
    {
        // Verificar si tiene al menos las piezas básicas asignadas
        $vestimentasRequeridas = ['POLLERA', 'CHAQUETA', 'SOMBRERO'];
        $vestimentasAsignadas = $this->vestimentas()
                                    ->whereHas('producto', function($query) use ($vestimentasRequeridas) {
                                        $query->whereIn('tipo_vestimenta', $vestimentasRequeridas);
                                    })
                                    ->count();

        return $vestimentasAsignadas >= count($vestimentasRequeridas);
    }

    public function generarNumeroParticipante()
    {
        $evento = $this->evento;
        $count = $evento->participantes()->count() + 1;
        return $evento->numero_evento . '-P' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function calcularSaldoPendiente()
    {
        $totalPagado = $this->obtenerTotalPagado();
        return $this->monto_participacion - $totalPagado;
    }

    public function obtenerTotalPagado()
    {
        // Aquí se podría implementar la lógica para obtener los pagos realizados
        // desde el sistema de caja basado en el estado_pago
        if ($this->estado_pago === self::PAGO_PAGADO) {
            return $this->monto_participacion;
        } elseif ($this->estado_pago === self::PAGO_PARCIAL) {
            // Lógica para obtener el monto parcial pagado
            return $this->monto_participacion * 0.5; // Ejemplo
        }
        return 0;
    }

    public function confirmarParticipacion()
    {
        $this->update([
            'estado_participante' => self::ESTADO_CONFIRMADO
        ]);
    }

    public function asignarVestimenta($productos)
    {
        foreach ($productos as $producto) {
            EventoVestimenta::create([
                'evento_id' => $this->evento_id,
                'participante_id' => $this->id,
                'producto_id' => $producto['id'],
                'sucursal_id' => $producto['sucursal_id'],
                'cantidad' => $producto['cantidad'] ?? 1,
            ]);
        }

        $this->update([
            'estado_participante' => self::ESTADO_VESTIMENTA_ASIGNADA
        ]);
    }

    public function finalizar()
    {
        // Marcar todas las vestimentas como devueltas
        $this->vestimentas()->update([
            'estado_vestimenta' => 'DEVUELTA',
            'fecha_devolucion' => now()
        ]);

        $this->update([
            'estado_participante' => self::ESTADO_FINALIZADO
        ]);

        // Liberar garantía si existe
        if ($this->garantia) {
            $this->garantia->liberarGarantia();
        }
    }

    public function obtenerResumen()
    {
        return [
            'numero_participante' => $this->numero_participante,
            'nombre_completo' => $this->nombre_completo,
            'talla' => $this->talla_general,
            'estado_participante' => $this->estado_participante,
            'estado_pago' => $this->estado_pago,
            'monto_participacion' => $this->monto_participacion,
            'saldo_pendiente' => $this->calcularSaldoPendiente(),
            'vestimentas_asignadas' => $this->vestimentas()->count(),
            'tiene_vestimenta_completa' => $this->tieneVestimentaCompleta(),
        ];
    }
}