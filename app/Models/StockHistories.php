<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistories extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'cantidad',
        'stock_anterior',
        'stock_nuevo',
        'tipo_movimiento',
        'referencia',
        'referencia_id',
        'observacion'
    ];
}
