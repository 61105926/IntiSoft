<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $guarded = [];

    use HasFactory;
    public function especies()
    {
        return $this->belongsTo(Especie::class, 'especie'); // Cambia `Especie` al nombre de tu modelo
    }

    // Definir relación con raza
    public function razas()
    {
        return $this->belongsTo(Raza::class, 'raza'); // Cambia `Raza` al nombre de tu modelo
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id'); // Cambia `Raza` al nombre de tu modelo
    }
}
