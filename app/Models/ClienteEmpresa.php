<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteEmpresa extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sucursal()
{
    return $this->belongsTo(Sucursal::class);
}

}
