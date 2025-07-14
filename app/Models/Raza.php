<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raza extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function especie()
    {
        return $this->belongsTo(Especie::class);
    }
}
