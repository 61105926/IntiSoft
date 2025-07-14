<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProveedorTransacionDetalle extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;


    public function transaction()
    {
        return $this->belongsTo(ProveedorTransacion::class);
    }

    // Un detalle pertenece a un producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    
}
