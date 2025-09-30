<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Garantia extends Model
{
    use HasFactory;

    protected $table = 'garantias';

    protected $fillable = [
        'tipo_garantia_id',
        'numero_ticket',
        'cliente_id',
        'descripcion',
        'monto',
        'documento_respaldo',
        'archivo_documento',
        'estado',
        'fecha_recepcion',
        'fecha_vencimiento',
        'fecha_devolucion',
        'monto_devuelto',
        'monto_aplicado',
        'usuario_recepcion',
        'usuario_devolucion',
        'sucursal_id',
        'observaciones',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'monto_devuelto' => 'decimal:2',
        'monto_aplicado' => 'decimal:2',
        'fecha_recepcion' => 'datetime',
        'fecha_vencimiento' => 'date',
        'fecha_devolucion' => 'datetime',
    ];

    // Valores del enum estado
    const ESTADO_RECIBIDA = 'RECIBIDA';
    const ESTADO_DEVUELTA = 'DEVUELTA';
    const ESTADO_PERDIDA = 'PERDIDA';
    const ESTADO_APLICADA = 'APLICADA';
    const ESTADO_VENCIDA = 'VENCIDA';

    public static $estados = [
        self::ESTADO_RECIBIDA => 'Recibida',
        self::ESTADO_DEVUELTA => 'Devuelta',
        self::ESTADO_PERDIDA => 'Perdida',
        self::ESTADO_APLICADA => 'Aplicada',
        self::ESTADO_VENCIDA => 'Vencida',
    ];

    // Relaciones
    public function tipoGarantia()
    {
        return $this->belongsTo(TipoGarantia::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function usuarioRecepcion()
    {
        return $this->belongsTo(User::class, 'usuario_recepcion');
    }

    public function usuarioDevolucion()
    {
        return $this->belongsTo(User::class, 'usuario_devolucion');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }


    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('estado', self::ESTADO_RECIBIDA);
    }

    public function scopeVencidas($query)
    {
        return $query->where('estado', self::ESTADO_VENCIDA)
                    ->orWhere(function($q) {
                        $q->where('estado', self::ESTADO_RECIBIDA)
                          ->where('fecha_vencimiento', '<', now()->toDateString());
                    });
    }

    public function scopeDevueltas($query)
    {
        return $query->where('estado', self::ESTADO_DEVUELTA);
    }

    public function scopePorSucursal($query, $sucursalId)
    {
        return $query->where('sucursal_id', $sucursalId);
    }

    public function scopePorCliente($query, $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }

    // Accessors
    public function getEstadoDisplayAttribute()
    {
        return self::$estados[$this->estado] ?? $this->estado;
    }

    public function getDiasVencidosAttribute()
    {
        if (!$this->fecha_vencimiento || $this->estado !== self::ESTADO_RECIBIDA) {
            return 0;
        }

        $hoy = Carbon::today();
        $fechaVencimiento = Carbon::parse($this->fecha_vencimiento);
        
        if ($hoy->gt($fechaVencimiento)) {
            return $hoy->diffInDays($fechaVencimiento);
        }
        
        return 0;
    }

    public function getDiasRestantesAttribute()
    {
        if (!$this->fecha_vencimiento || $this->estado !== self::ESTADO_RECIBIDA) {
            return 0;
        }

        $hoy = Carbon::today();
        $fechaVencimiento = Carbon::parse($this->fecha_vencimiento);
        
        if ($fechaVencimiento->gt($hoy)) {
            return $hoy->diffInDays($fechaVencimiento, false); // Negativo significa días restantes
        }
        
        return 0;
    }

    public function getMontoDisponibleAttribute()
    {
        return $this->monto - $this->monto_aplicado - $this->monto_devuelto;
    }

    public function getEstaVencidaAttribute()
    {
        return $this->fecha_vencimiento && 
               Carbon::parse($this->fecha_vencimiento)->isPast() && 
               $this->estado === self::ESTADO_RECIBIDA;
    }

    public function getPuedeUsarseAttribute()
    {
        return $this->estado === self::ESTADO_RECIBIDA && !$this->esta_vencida;
    }

    // Métodos
    public function generarNumeroTicket($sucursalId = null)
    {
        $sucursal = $sucursalId ?? $this->sucursal_id;
        $codigo = Sucursal::find($sucursal)->codigo ?? 'GEN';
        $correlativo = self::where('sucursal_id', $sucursal)
                          ->whereYear('created_at', now()->year)
                          ->count() + 1;
        
        return "GAR-{$codigo}-" . date('Y') . '-' . str_pad($correlativo, 4, '0', STR_PAD_LEFT);
    }

    public function marcarComoDevuelta($montoDevuelto = null, $observaciones = null)
    {
        $this->update([
            'estado' => self::ESTADO_DEVUELTA,
            'fecha_devolucion' => now(),
            'monto_devuelto' => $montoDevuelto ?? $this->monto_disponible,
            'usuario_devolucion' => auth()->id(),
            'observaciones' => $this->observaciones . ($observaciones ? "\nDevolución: " . $observaciones : ''),
        ]);

        return $this;
    }

    public function aplicarMonto($monto, $motivo = null)
    {
        if ($monto > $this->monto_disponible) {
            throw new \Exception('No hay suficiente monto disponible en la garantía.');
        }

        $this->increment('monto_aplicado', $monto);
        
        if ($this->monto_disponible <= 0) {
            $this->update([
                'estado' => self::ESTADO_APLICADA,
                'observaciones' => $this->observaciones . "\nMonto aplicado: " . ($motivo ?? 'Sin especificar'),
            ]);
        }

        return $this;
    }

    public function marcarComoPerdida($observaciones = null)
    {
        $this->update([
            'estado' => self::ESTADO_PERDIDA,
            'observaciones' => $this->observaciones . ($observaciones ? "\nPérdida: " . $observaciones : ''),
        ]);

        return $this;
    }

    public function actualizarEstado()
    {
        if ($this->estado === self::ESTADO_RECIBIDA && $this->esta_vencida) {
            $this->update(['estado' => self::ESTADO_VENCIDA]);
        }

        return $this;
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($garantia) {
            if (!$garantia->numero_ticket) {
                $garantia->numero_ticket = $garantia->generarNumeroTicket($garantia->sucursal_id);
            }
            
            if (!$garantia->fecha_vencimiento && $garantia->tipoGarantia) {
                $garantia->fecha_vencimiento = $garantia->tipoGarantia->calcularFechaVencimiento();
            }
        });
    }
}