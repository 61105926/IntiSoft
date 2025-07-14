<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CajaSalida extends Model
{
    use HasFactory;
    protected $fillable = [
        'caja_id',
        'type',
        'monto',
        'description',
    ];
}
