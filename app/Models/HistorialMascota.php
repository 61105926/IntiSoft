<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialMascota extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function documentos()
    {
        return $this->hasMany(HistorialArchivos::class, 'historial_id');
    }

}
