<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleteProgramado extends Model
{
    use HasFactory;

    protected $table = 'fletes_programados';

    protected $fillable = [
        'alquiler_id',
        'evento_id',
        'numero_flete',
        'tipo_flete',
        'estado_flete',
        'direccion_entrega',
        'referencia_entrega',
        'fecha_entrega_programada',
        'fecha_entrega_real',
        'contacto_entrega',
        'telefono_entrega',
        'direccion_recogida',
        'referencia_recogida',
        'fecha_recogida_programada',
        'fecha_recogida_real',
        'contacto_recogida',
        'telefono_recogida',
        'costo_entrega',
        'costo_recogida',
        'costo_total',
        'vehiculo_tipo',
        'conductor_nombre',
        'conductor_telefono',
        'observaciones',
        'evidencias',
        'usuario_programacion',
        'usuario_entrega',
        'usuario_recogida',
    ];

    protected $casts = [
        'fecha_entrega_programada' => 'datetime',
        'fecha_entrega_real' => 'datetime',
        'fecha_recogida_programada' => 'datetime',
        'fecha_recogida_real' => 'datetime',
        'costo_entrega' => 'decimal:2',
        'costo_recogida' => 'decimal:2',
        'costo_total' => 'decimal:2',
        'evidencias' => 'array',
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

    public function usuarioProgramacion()
    {
        return $this->belongsTo(User::class, 'usuario_programacion');
    }

    public function usuarioEntrega()
    {
        return $this->belongsTo(User::class, 'usuario_entrega');
    }

    public function usuarioRecogida()
    {
        return $this->belongsTo(User::class, 'usuario_recogida');
    }

    // Scopes
    public function scopeProgramados($query)
    {
        return $query->where('estado_flete', 'PROGRAMADO');
    }

    public function scopeEnRuta($query)
    {
        return $query->where('estado_flete', 'EN_RUTA');
    }

    public function scopeCompletados($query)
    {
        return $query->where('estado_flete', 'COMPLETADO');
    }

    public function scopePorFecha($query, $fecha)
    {
        return $query->where(function($q) use ($fecha) {
            $q->whereDate('fecha_entrega_programada', $fecha)
              ->orWhereDate('fecha_recogida_programada', $fecha);
        });
    }

    public function scopeProximosVencer($query, $horas = 24)
    {
        $limite = now()->addHours($horas);
        return $query->where('estado_flete', 'PROGRAMADO')
                    ->where(function($q) use ($limite) {
                        $q->where('fecha_entrega_programada', '<=', $limite)
                          ->orWhere('fecha_recogida_programada', '<=', $limite);
                    });
    }

    // Accessors
    public function getReferenciaAttribute()
    {
        if ($this->alquiler_id) {
            return "ALQ-{$this->alquiler_id}";
        } elseif ($this->evento_id) {
            return "EVT-{$this->evento_id}";
        }
        return "SIN-REF";
    }

    public function getClienteAttribute()
    {
        if ($this->alquiler) {
            return $this->alquiler->cliente;
        } elseif ($this->evento) {
            return $this->evento; // Los eventos no tienen un cliente único
        }
        return null;
    }

    public function getEsEntregaAttribute()
    {
        return in_array($this->tipo_flete, ['ENTREGA', 'AMBOS']);
    }

    public function getEsRecogidaAttribute()
    {
        return in_array($this->tipo_flete, ['RECOGIDA', 'AMBOS']);
    }

    public function getProgresoAttribute()
    {
        switch ($this->estado_flete) {
            case 'PROGRAMADO':
                return 10;
            case 'EN_RUTA':
                return 50;
            case 'ENTREGADO':
                return $this->es_recogida ? 75 : 100;
            case 'RECOGIDO':
                return 90;
            case 'COMPLETADO':
                return 100;
            case 'CANCELADO':
                return 0;
            default:
                return 0;
        }
    }

    // Métodos de negocio
    public function puedeIniciarEntrega()
    {
        return $this->estado_flete === 'PROGRAMADO' && $this->es_entrega;
    }

    public function puedeIniciarRecogida()
    {
        return in_array($this->estado_flete, ['PROGRAMADO', 'ENTREGADO']) && $this->es_recogida;
    }

    public function iniciarEntrega($usuarioId, $observaciones = null)
    {
        if (!$this->puedeIniciarEntrega()) {
            throw new \Exception('No se puede iniciar la entrega en el estado actual.');
        }

        $this->update([
            'estado_flete' => 'EN_RUTA',
            'usuario_entrega' => $usuarioId,
            'observaciones' => $this->observaciones . "\nEntrega iniciada: " . ($observaciones ?? ''),
        ]);

        return $this;
    }

    public function completarEntrega($evidencias = [], $observaciones = null)
    {
        if ($this->estado_flete !== 'EN_RUTA') {
            throw new \Exception('El flete debe estar en ruta para completar la entrega.');
        }

        $nuevoEstado = $this->es_recogida ? 'ENTREGADO' : 'COMPLETADO';

        $this->update([
            'estado_flete' => $nuevoEstado,
            'fecha_entrega_real' => now(),
            'evidencias' => array_merge($this->evidencias ?? [], $evidencias),
            'observaciones' => $this->observaciones . "\nEntrega completada: " . ($observaciones ?? ''),
        ]);

        return $this;
    }

    public function iniciarRecogida($usuarioId, $observaciones = null)
    {
        if (!$this->puedeIniciarRecogida()) {
            throw new \Exception('No se puede iniciar la recogida en el estado actual.');
        }

        $this->update([
            'estado_flete' => 'EN_RUTA',
            'usuario_recogida' => $usuarioId,
            'observaciones' => $this->observaciones . "\nRecogida iniciada: " . ($observaciones ?? ''),
        ]);

        return $this;
    }

    public function completarRecogida($evidencias = [], $observaciones = null)
    {
        if (!$this->es_recogida) {
            throw new \Exception('Este flete no incluye recogida.');
        }

        $this->update([
            'estado_flete' => 'COMPLETADO',
            'fecha_recogida_real' => now(),
            'evidencias' => array_merge($this->evidencias ?? [], $evidencias),
            'observaciones' => $this->observaciones . "\nRecogida completada: " . ($observaciones ?? ''),
        ]);

        // Actualizar estado del alquiler si corresponde
        if ($this->alquiler) {
            $this->alquiler->update(['estado' => 'DEVUELTO']);
        }

        return $this;
    }

    public function cancelar($motivo)
    {
        if (in_array($this->estado_flete, ['COMPLETADO', 'CANCELADO'])) {
            throw new \Exception('No se puede cancelar un flete completado o ya cancelado.');
        }

        $this->update([
            'estado_flete' => 'CANCELADO',
            'observaciones' => $this->observaciones . "\nFlete cancelado: {$motivo}",
        ]);

        return $this;
    }

    // Métodos estáticos
    public static function generarNumeroFlete()
    {
        $año = date('Y');
        $ultimo = self::where('numero_flete', 'like', "FLT-{$año}-%")
                     ->orderBy('numero_flete', 'desc')
                     ->first();

        $siguienteNumero = 1;
        if ($ultimo) {
            $ultimoNumero = intval(substr($ultimo->numero_flete, -6));
            $siguienteNumero = $ultimoNumero + 1;
        }

        return "FLT-{$año}-" . str_pad($siguienteNumero, 6, '0', STR_PAD_LEFT);
    }

    public static function programarFlete($datos)
    {
        $numero = self::generarNumeroFlete();

        // Calcular costo total
        $costoTotal = ($datos['costo_entrega'] ?? 0) + ($datos['costo_recogida'] ?? 0);

        return self::create(array_merge($datos, [
            'numero_flete' => $numero,
            'costo_total' => $costoTotal,
            'estado_flete' => 'PROGRAMADO',
        ]));
    }

    public static function obtenerFletesPendientes()
    {
        return self::where('estado_flete', 'PROGRAMADO')
                  ->where(function($q) {
                      $q->whereDate('fecha_entrega_programada', '<=', now()->addDays(1))
                        ->orWhereDate('fecha_recogida_programada', '<=', now()->addDays(1));
                  })
                  ->with(['alquiler.cliente', 'evento'])
                  ->orderBy('fecha_entrega_programada')
                  ->get();
    }
}