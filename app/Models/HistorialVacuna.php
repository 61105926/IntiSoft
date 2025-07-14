<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialVacuna extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function vacunas()
{
    return $this->belongsTo(Vacuna::class,'vacuna_id');
}
}
