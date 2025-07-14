<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function venta()
    {
        return $this->belongsTo(Ventas::class);
    }

  
    public function producto()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
