<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EntradaFolcloricaGarantia extends Model
{
    use HasFactory;

    protected $fillable = [
        'entrada_folclorica_id',
        'entrada_detalle_id',
        'numero_garantia',
        'nombre_participante',
        'telefono_participante',
        'documento_identidad',
        'monto_garantia',
        'monto_disponible',
        'monto_usado',
        'monto_devuelto',
        'estado',
        'fecha_creacion_garantia',
        'fecha_devolucion_garantia',
        'metodo_pago',
        'referencia_pago',
        'observaciones_creacion',
        'observaciones_devolucion',
        'motivo_uso_garantia',
        'usuario_creacion',
        'usuario_devolucion',
    ];

    protected $casts = [
        'monto_garantia' => 'decimal:2',
        'monto_disponible' => 'decimal:2',
        'monto_usado' => 'decimal:2',
        'monto_devuelto' => 'decimal:2',
        'fecha_creacion_garantia' => 'datetime',
        'fecha_devolucion_garantia' => 'datetime',
    ];

    // Estados disponibles
    const ESTADO_ACTIVA = 'ACTIVA';
    const ESTADO_DEVUELTA_PARCIAL = 'DEVUELTA_PARCIAL';
    const ESTADO_DEVUELTA_COMPLETA = 'DEVUELTA_COMPLETA';
    const ESTADO_APLICADA = 'APLICADA';

    // Métodos de pago
    const METODO_EFECTIVO = 'EFECTIVO';
    const METODO_TRANSFERENCIA = 'TRANSFERENCIA';
    const METODO_TARJETA = 'TARJETA';
    const METODO_QR = 'QR';

    // Relaciones
    public function entradaFolclorica()
    {
        return $this->belongsTo(EntradaFolclorica::class);
    }

    public function entradaDetalle()
    {
        return $this->belongsTo(EntradaFolcloricaDetalle::class);
    }

    public function usuarioCreacion()
    {
        return $this->belongsTo(User::class, 'usuario_creacion');
    }

    public function usuarioDevolucion()
    {
        return $this->belongsTo(User::class, 'usuario_devolucion');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('estado', self::ESTADO_ACTIVA);
    }

    public function scopeDevueltas($query)
    {
        return $query->whereIn('estado', [self::ESTADO_DEVUELTA_PARCIAL, self::ESTADO_DEVUELTA_COMPLETA]);
    }

    public function scopePorParticipante($query, $documento)
    {
        return $query->where('documento_identidad', $documento);
    }

    // Accessors
    public function getEstadoDisplayAttribute()
    {
        return match($this->estado) {
            self::ESTADO_ACTIVA => 'Activa',
            self::ESTADO_DEVUELTA_PARCIAL => 'Devuelta Parcial',
            self::ESTADO_DEVUELTA_COMPLETA => 'Devuelta Completa',
            self::ESTADO_APLICADA => 'Aplicada',
            default => $this->estado
        };
    }

    public function getEstadoBadgeClassAttribute()
    {
        return match($this->estado) {
            self::ESTADO_ACTIVA => 'bg-success',
            self::ESTADO_DEVUELTA_PARCIAL => 'bg-warning',
            self::ESTADO_DEVUELTA_COMPLETA => 'bg-info',
            self::ESTADO_APLICADA => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    public function getMetodoPagoDisplayAttribute()
    {
        return match($this->metodo_pago) {
            self::METODO_EFECTIVO => 'Efectivo',
            self::METODO_TRANSFERENCIA => 'Transferencia',
            self::METODO_TARJETA => 'Tarjeta',
            self::METODO_QR => 'QR',
            default => $this->metodo_pago
        };
    }

    public function getPuedeSerDevueltaAttribute()
    {
        return $this->monto_disponible > 0 && in_array($this->estado, [self::ESTADO_ACTIVA, self::ESTADO_DEVUELTA_PARCIAL]);
    }

    // Métodos de negocio
    public function generarNumeroGarantia()
    {
        $entradaNumero = $this->entradaFolclorica->numero_entrada;
        $participanteNumero = str_pad($this->entrada_detalle_id, 3, '0', STR_PAD_LEFT);
        
        return "GAR-{$entradaNumero}-{$participanteNumero}";
    }

    public function aplicarPenalizacion($monto, $motivo = null)
    {
        if ($this->monto_disponible >= $monto) {
            $this->monto_usado += $monto;
            $this->monto_disponible -= $monto;
            $this->motivo_uso_garantia = $motivo;
            
            // Actualizar estado
            if ($this->monto_disponible <= 0) {
                $this->estado = self::ESTADO_APLICADA;
            } else {
                $this->estado = self::ESTADO_DEVUELTA_PARCIAL;
            }
            
            $this->save();
            
            // Actualizar totales de la entrada folclórica
            $this->entradaFolclorica->calcularTotales();
            $this->entradaFolclorica->save();
            
            return true;
        }
        
        return false;
    }

    public function procesarDevolucion($montoDevolver, $observaciones = null)
    {
        if ($montoDevolver <= $this->monto_disponible) {
            $this->monto_devuelto += $montoDevolver;
            $this->monto_disponible -= $montoDevolver;
            $this->fecha_devolucion_garantia = Carbon::now();
            $this->observaciones_devolucion = $observaciones;
            $this->usuario_devolucion = auth()->id();
            
            // Actualizar estado
            if ($this->monto_disponible <= 0) {
                $this->estado = self::ESTADO_DEVUELTA_COMPLETA;
            } else {
                $this->estado = self::ESTADO_DEVUELTA_PARCIAL;
            }
            
            $this->save();
            
            // Actualizar totales de la entrada folclórica
            $entrada = $this->entradaFolclorica;
            $entrada->garantias_devueltas += $montoDevolver;
            $entrada->calcularTotales();
            $entrada->save();
            
            return true;
        }
        
        return false;
    }

    public function restaurarMonto($monto, $motivo = null)
    {
        // Para casos donde se necesite restaurar dinero a la garantía
        $this->monto_disponible += $monto;
        $this->monto_usado = max(0, $this->monto_usado - $monto);
        $this->estado = self::ESTADO_ACTIVA;
        $this->motivo_uso_garantia = $motivo;
        $this->save();
        
        // Actualizar totales de la entrada folclórica
        $this->entradaFolclorica->calcularTotales();
        $this->entradaFolclorica->save();
    }

    // Métodos estáticos
    public static function obtenerEstadosDisponibles()
    {
        return [
            self::ESTADO_ACTIVA => 'Activa',
            self::ESTADO_DEVUELTA_PARCIAL => 'Devuelta Parcial',
            self::ESTADO_DEVUELTA_COMPLETA => 'Devuelta Completa',
            self::ESTADO_APLICADA => 'Aplicada'
        ];
    }

    public static function obtenerMetodosPago()
    {
        return [
            self::METODO_EFECTIVO => 'Efectivo',
            self::METODO_TRANSFERENCIA => 'Transferencia',
            self::METODO_TARJETA => 'Tarjeta',
            self::METODO_QR => 'QR'
        ];
    }
}