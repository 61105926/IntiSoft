<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EntradaFolclorica extends Model
{
    use HasFactory;

    protected $table = 'entradas_folcloricas';

    protected $fillable = [
        'numero_entrada',
        'sucursal_id',
        'nombre_evento',
        'descripcion_evento',
        'fecha_evento',
        'hora_evento',
        'lugar_evento',
        'cliente_responsable_id',
        'contacto_nombre',
        'contacto_telefono',
        'contacto_email',
        'fecha_entrega',
        'hora_entrega',
        'fecha_devolucion_programada',
        'hora_devolucion_programada',
        'fecha_devolucion_real',
        'cantidad_participantes',
        'subtotal_general',
        'descuento_general',
        'total_general',
        'anticipo_total',
        'saldo_pendiente',
        'monto_garantia_individual',
        'total_garantias',
        'garantias_devueltas',
        'garantias_pendientes',
        'estado',
        'estado_pago',
        'estado_garantias',
        'observaciones',
        'condiciones_especiales',
        'usuario_creacion',
        'usuario_entrega',
        'usuario_devolucion',
    ];

    protected $casts = [
        'fecha_evento' => 'date',
        'hora_evento' => 'datetime:H:i',
        'fecha_entrega' => 'date',
        'hora_entrega' => 'datetime:H:i',
        'fecha_devolucion_programada' => 'date',
        'hora_devolucion_programada' => 'datetime:H:i',
        'fecha_devolucion_real' => 'datetime',
        'subtotal_general' => 'decimal:2',
        'descuento_general' => 'decimal:2',
        'total_general' => 'decimal:2',
        'anticipo_total' => 'decimal:2',
        'saldo_pendiente' => 'decimal:2',
        'monto_garantia_individual' => 'decimal:2',
        'total_garantias' => 'decimal:2',
        'garantias_devueltas' => 'decimal:2',
        'garantias_pendientes' => 'decimal:2',
    ];

    // Estados disponibles
    const ESTADO_ACTIVO = 'ACTIVO';
    const ESTADO_DEVUELTO_PARCIAL = 'DEVUELTO_PARCIAL';
    const ESTADO_DEVUELTO_COMPLETO = 'DEVUELTO_COMPLETO';
    const ESTADO_VENCIDO = 'VENCIDO';
    const ESTADO_CANCELADO = 'CANCELADO';

    const ESTADO_PAGO_PENDIENTE = 'PENDIENTE';
    const ESTADO_PAGO_PARCIAL = 'PARCIAL';
    const ESTADO_PAGO_PAGADO = 'PAGADO';

    const ESTADO_GARANTIAS_PENDIENTE = 'PENDIENTE';
    const ESTADO_GARANTIAS_PARCIAL = 'PARCIAL';
    const ESTADO_GARANTIAS_DEVUELTO_COMPLETO = 'DEVUELTO_COMPLETO';

    // Relaciones
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function clienteResponsable()
    {
        return $this->belongsTo(Cliente::class, 'cliente_responsable_id');
    }

    public function usuarioCreacion()
    {
        return $this->belongsTo(User::class, 'usuario_creacion');
    }

    public function usuarioEntrega()
    {
        return $this->belongsTo(User::class, 'usuario_entrega');
    }

    public function usuarioDevolucion()
    {
        return $this->belongsTo(User::class, 'usuario_devolucion');
    }

    public function detalles()
    {
        return $this->hasMany(EntradaFolcloricaDetalle::class);
    }

    public function garantias()
    {
        return $this->hasMany(EntradaFolcloricaGarantia::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estado', self::ESTADO_ACTIVO);
    }

    public function scopePorVencer($query, $dias = 3)
    {
        return $query->where('fecha_devolucion_programada', '<=', Carbon::now()->addDays($dias))
                    ->where('estado', self::ESTADO_ACTIVO);
    }

    public function scopeVencidos($query)
    {
        return $query->where('fecha_devolucion_programada', '<', Carbon::today())
                    ->where('estado', self::ESTADO_ACTIVO);
    }

    public function scopeEventosHoy($query)
    {
        return $query->whereDate('fecha_evento', Carbon::today());
    }

    // Accessors
    public function getEstadoDisplayAttribute()
    {
        return match($this->estado) {
            self::ESTADO_ACTIVO => 'Activo',
            self::ESTADO_DEVUELTO_PARCIAL => 'Devuelto Parcial',
            self::ESTADO_DEVUELTO_COMPLETO => 'Devuelto Completo',
            self::ESTADO_VENCIDO => 'Vencido',
            self::ESTADO_CANCELADO => 'Cancelado',
            default => $this->estado
        };
    }

    public function getEstadoPagoDisplayAttribute()
    {
        return match($this->estado_pago) {
            self::ESTADO_PAGO_PENDIENTE => 'Pendiente',
            self::ESTADO_PAGO_PARCIAL => 'Parcial',
            self::ESTADO_PAGO_PAGADO => 'Pagado',
            default => $this->estado_pago
        };
    }

    public function getEstadoGarantiasDisplayAttribute()
    {
        return match($this->estado_garantias) {
            self::ESTADO_GARANTIAS_PENDIENTE => 'Pendiente',
            self::ESTADO_GARANTIAS_PARCIAL => 'Parcial',
            self::ESTADO_GARANTIAS_DEVUELTO_COMPLETO => 'Devuelto Completo',
            default => $this->estado_garantias
        };
    }

    public function getEstadoBadgeClassAttribute()
    {
        return match($this->estado) {
            self::ESTADO_ACTIVO => 'bg-success',
            self::ESTADO_DEVUELTO_PARCIAL => 'bg-warning',
            self::ESTADO_DEVUELTO_COMPLETO => 'bg-info',
            self::ESTADO_VENCIDO => 'bg-danger',
            self::ESTADO_CANCELADO => 'bg-secondary',
            default => 'bg-secondary'
        };
    }

    public function getDiasRestantesAttribute()
    {
        if ($this->fecha_devolucion_programada) {
            return Carbon::parse($this->fecha_devolucion_programada)->diffInDays(Carbon::now(), false);
        }
        return null;
    }

    public function getEsVencidoAttribute()
    {
        return $this->fecha_devolucion_programada < Carbon::today() && $this->estado === self::ESTADO_ACTIVO;
    }

    // Métodos de negocio
    public function generarNumeroEntrada()
    {
        $sucursalId = str_pad($this->sucursal_id, 2, '0', STR_PAD_LEFT);
        $fechaEvento = Carbon::parse($this->fecha_evento)->format('Ymd');
        
        // Obtener el último número del día
        $ultimaEntrada = self::where('sucursal_id', $this->sucursal_id)
            ->whereDate('fecha_evento', Carbon::parse($this->fecha_evento))
            ->orderBy('id', 'desc')
            ->first();
        
        $siguiente = 1;
        if ($ultimaEntrada) {
            $partes = explode('-', $ultimaEntrada->numero_entrada);
            if (count($partes) >= 3) {
                $siguiente = intval($partes[2]) + 1;
            }
        }
        
        return "FOLK-{$sucursalId}{$fechaEvento}-" . str_pad($siguiente, 4, '0', STR_PAD_LEFT);
    }

    public function calcularTotales()
    {
        $subtotal = $this->detalles->sum('subtotal');
        $this->subtotal_general = $subtotal;
        $this->total_general = $subtotal - $this->descuento_general;
        $this->saldo_pendiente = $this->total_general - $this->anticipo_total;
        
        // Calcular garantías
        $this->total_garantias = $this->cantidad_participantes * $this->monto_garantia_individual;
        $this->garantias_pendientes = $this->total_garantias - $this->garantias_devueltas;
        
        // Actualizar estados
        if ($this->anticipo_total >= $this->total_general) {
            $this->estado_pago = self::ESTADO_PAGO_PAGADO;
        } elseif ($this->anticipo_total > 0) {
            $this->estado_pago = self::ESTADO_PAGO_PARCIAL;
        } else {
            $this->estado_pago = self::ESTADO_PAGO_PENDIENTE;
        }

        if ($this->garantias_devueltas >= $this->total_garantias) {
            $this->estado_garantias = self::ESTADO_GARANTIAS_DEVUELTO_COMPLETO;
        } elseif ($this->garantias_devueltas > 0) {
            $this->estado_garantias = self::ESTADO_GARANTIAS_PARCIAL;
        }
    }

    public function marcarComoVencido()
    {
        $this->estado = self::ESTADO_VENCIDO;
        $this->save();
    }

    public function procesarDevolucion($detallesDevueltos = [])
    {
        $totalDetalles = $this->detalles->count();
        $detallesDevueltosCount = count($detallesDevueltos);
        
        if ($detallesDevueltosCount >= $totalDetalles) {
            $this->estado = self::ESTADO_DEVUELTO_COMPLETO;
            $this->fecha_devolucion_real = Carbon::now();
        } elseif ($detallesDevueltosCount > 0) {
            $this->estado = self::ESTADO_DEVUELTO_PARCIAL;
        }
        
        $this->save();
    }

    // Métodos estáticos
    public static function obtenerEstadosDisponibles()
    {
        return [
            self::ESTADO_ACTIVO => 'Activo',
            self::ESTADO_DEVUELTO_PARCIAL => 'Devuelto Parcial',
            self::ESTADO_DEVUELTO_COMPLETO => 'Devuelto Completo',
            self::ESTADO_VENCIDO => 'Vencido',
            self::ESTADO_CANCELADO => 'Cancelado'
        ];
    }
}