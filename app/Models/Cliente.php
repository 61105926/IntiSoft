<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function clienteEmpresa()
    {
        return $this->hasOne(ClienteEmpresa::class);
    }

    public function unidadEducativa()
    {
        return $this->hasOne(UnidadEducativa::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id'); // o como tengas tu relación con usuario creador
    }

    public function getRazonSocialAttribute()
    {
        if ($this->tipo_cliente === 'INDIVIDUAL') {
            return $this->nombres . ' ' . $this->apellidos;
        }
        if ($this->tipo_cliente === 'EMPRESA') {
            return $this->empresa->razon_social ?? '';
        }
        if ($this->tipo_cliente === 'UNIDAD_EDUCATIVA') {
            return $this->unidadEducativa->nombre ?? '';
        }
        return '';
    }

    public function getNitAttribute()
    {
        if ($this->tipo_cliente === 'EMPRESA') {
            return $this->empresa->nit ?? '';
        }
        if ($this->tipo_cliente === 'INDIVIDUAL') {
            return $this->carnet_identidad;
        }
        if ($this->tipo_cliente === 'UNIDAD_EDUCATIVA') {
            return $this->unidadEducativa->codigo ?? '';
        }
        return '';
    }

    public function getTelefonoPrincipalAttribute()
    {
        if ($this->tipo_cliente === 'EMPRESA') {
            return $this->empresa->telefono_principal ?? '';
        }
        if ($this->tipo_cliente === 'INDIVIDUAL') {
            return $this->telefono;
        }
        if ($this->tipo_cliente === 'UNIDAD_EDUCATIVA') {
            return $this->unidadEducativa->telefono ?? '';
        }
        return '';
    }

    public function getTelefonoSecundarioAttribute()
    {
        if ($this->tipo_cliente === 'EMPRESA') {
            return $this->empresa->telefono_secundario ?? '';
        }
        return null;
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
}
