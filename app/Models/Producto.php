<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $guarded = [];

    use HasFactory;

    protected $casts = [
        'imagenes_adicionales' => 'array',
        'disponible_venta' => 'boolean',
        'disponible_alquiler' => 'boolean',
    ];
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaProducto::class, 'categoria_id');
    }
    public function stocks()
    {
        return $this->hasMany(StockPorSucursal::class, 'producto_id');
    }
    public function creador()
    {
        return $this->belongsTo(User::class, 'usuario_creacion');
    }

    public function stockPorSucursal()
    {
        return $this->hasOne(StockPorSucursal::class)->where('sucursal_id', $this->sucursal_id);
    }

    public function movimientosStock()
    {
        return $this->hasMany(MovimientoStockSucursal::class);
    }

    public function detalleTransferencias()
    {
        return $this->hasMany(DetalleTransferencia::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function getStockEnSucursal($sucursalId)
    {
        return $this->stockPorSucursal()->where('sucursal_id', $sucursalId)->first();
    }

    public function getTotalStock()
    {
        return $this->stockPorSucursal()->sum('stock_actual');
    }

    public function getPrecioVentaAttribute()
    {
        // Precio por defecto de la primera sucursal disponible
        $stock = $this->stocks()->first();
        return $stock ? $stock->precio_venta_sucursal ?? 0 : 0;
    }

    public function getPrecioVentaEnSucursal($sucursalId)
    {
        $stock = $this->getStockEnSucursal($sucursalId);
        return $stock ? $stock->precio_venta_sucursal ?? 0 : 0;
    }

    public function getImagenPrincipalUrlAttribute()
    {
        if ($this->imagen_principal) {
            return asset('storage/' . $this->imagen_principal);
        }
        return asset('images/produto-default.jpg');
    }

    public function getImagenesAdicionalesUrlsAttribute()
    {
        if ($this->imagenes_adicionales && is_array($this->imagenes_adicionales)) {
            return array_map(function($imagen) {
                return asset('storage/' . $imagen);
            }, $this->imagenes_adicionales);
        }
        return [];
    }

    public function getTodasImagenesAttribute()
    {
        $imagenes = [$this->imagen_principal_url];
        return array_merge($imagenes, $this->imagenes_adicionales_urls);
    }
}
