<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;
    protected $fillable = [
        'numero_reserva',
        'cliente_id',
        'tipo_reserva',
        'fecha_reserva',
        'fecha_vencimiento',
        'monto_efectivo',
        'total_estimado',
        'observaciones',
        'sucursal_id',
        'usuario_creacion_id',
        'estado',
    ];

    protected $casts = [
        'fecha_reserva' => 'date',
        'fecha_vencimiento' => 'date',
        'monto_efectivo' => 'decimal:2',
        'total_estimado' => 'decimal:2',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function usuarioCreacion()
    {
        return $this->belongsTo(User::class, 'usuario_creacion_id');
    }

    public function detalles()
    {
        return $this->hasMany(ReservaDetalle::class);
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('estado', 'ACTIVA');
    }

    public function scopeProximasVencer($query)
    {
        return $query->where('estado', 'PROXIMA_VENCER');
    }

    public function scopeVencidas($query)
    {
        return $query->where('estado', 'VENCIDA');
    }

    // Accessors
    public function getDiasRestantesAttribute()
    {
        return $this->fecha_vencimiento->diffInDays(now(), false);
    }

    public function getSaldoPendienteAttribute()
    {
        return $this->total_estimado - $this->monto_efectivo;
    }

    // Mutators y métodos
    public function actualizarEstado()
    {
        $diasRestantes = $this->dias_restantes;

        if ($diasRestantes <= 0 && $this->estado === 'ACTIVA') {
            $this->update(['estado' => 'VENCIDA']);
        } elseif ($diasRestantes <= 2 && $this->estado === 'ACTIVA') {
            $this->update(['estado' => 'PROXIMA_VENCER']);
        }
    }
}
