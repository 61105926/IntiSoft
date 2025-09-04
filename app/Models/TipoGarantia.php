<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoGarantia extends Model
{
    use HasFactory;

    protected $table = 'tipos_garantia';

    protected $fillable = [
        'nombre',
        'descripcion',
        'requiere_monto',
        'monto_minimo',
        'monto_maximo',
        'dias_devolucion',
        'activo',
    ];

    protected $casts = [
        'requiere_monto' => 'boolean',
        'monto_minimo' => 'decimal:2',
        'monto_maximo' => 'decimal:2',
        'activo' => 'boolean',
    ];

    // Relaciones
    public function garantias()
    {
        return $this->hasMany(Garantia::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeConMonto($query)
    {
        return $query->where('requiere_monto', true);
    }

    public function scopeSinMonto($query)
    {
        return $query->where('requiere_monto', false);
    }

    // Métodos
    public function validarMonto($monto)
    {
        if (!$this->requiere_monto) {
            return true;
        }

        if ($this->monto_minimo > 0 && $monto < $this->monto_minimo) {
            return false;
        }

        if ($this->monto_maximo > 0 && $monto > $this->monto_maximo) {
            return false;
        }

        return true;
    }

    public function calcularFechaVencimiento($fechaBase = null)
    {
        $fecha = $fechaBase ? \Carbon\Carbon::parse($fechaBase) : now();
        return $fecha->addDays($this->dias_devolucion);
    }

    // Accessors
    public function getRangoMontoAttribute()
    {
        if (!$this->requiere_monto) {
            return 'No requiere monto';
        }

        $min = $this->monto_minimo > 0 ? 'Bs. ' . number_format($this->monto_minimo, 2) : 'Sin mínimo';
        $max = $this->monto_maximo > 0 ? 'Bs. ' . number_format($this->monto_maximo, 2) : 'Sin máximo';
        
        return "{$min} - {$max}";
    }

    public function getEstadoDisplayAttribute()
    {
        return $this->activo ? 'Activo' : 'Inactivo';
    }
}