<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleTransferencia extends Model
{
    use HasFactory;

    public function transferencia()
    {
        return $this->belongsTo(TransferenciaSucursal::class, 'transferencia_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function getCantidadPendienteAttribute()
    {
        return $this->cantidad_solicitada - $this->cantidad_enviada;
    }

    public function getCantidadFaltanteAttribute()
    {
        return $this->cantidad_enviada - $this->cantidad_recibida;
    }
}
