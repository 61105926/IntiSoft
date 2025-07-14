<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CajaOperaciones extends Model
{
    use HasFactory;
    protected $fillable = [
        'caja_id',
        'type',
        'monto',
        'description',
    ];
    public function user()
    {
        return $this->belongsTo(Caja::class);
    }
}
