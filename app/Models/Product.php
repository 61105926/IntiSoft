<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
    public function transactionDetails()
    {
        return $this->hasMany(ProveedorTransacionDetalle::class);
    }
    public function stockHistories()
    {
        return $this->hasMany(StockHistories::class, 'product_id');
    }
}
