<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferenciaSucursal extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function sucursalOrigen()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_origen_id');
    }

    public function sucursalDestino()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_destino_id');
    }

    public function usuarioSolicita()
    {
        return $this->belongsTo(User::class, 'usuario_solicita_id');
    }

    public function detalleTransferencias()
    {
        return $this->hasMany(DetalleTransferencia::class, 'transferencia_id');
    }
}
