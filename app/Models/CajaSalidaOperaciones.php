<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CajaSalidaOperaciones extends Model
{
    use HasFactory;
    protected $fillable = [
        'caja_id',
        'type',
        'monto',
        'description',
        'created_at',
    ];
}
