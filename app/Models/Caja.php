<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'description', 'state', 'total_salida', 'total_entrada', 'total_entrada'];
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function cajasEntrada()
    {
        return $this->hasMany(CajaEntrada::class);
    }
    public function cajasOperaciones()
    {
        return $this->hasMany(CajaOperaciones::class);
    }
    public function cajasSalidaOperaciones()
    {
        return $this->hasMany(CajaSalidaOperaciones::class);
    }

    public function calculteEntradasSaldias()
    {
        // Sumar solo las entradas activas (estado = 1)
        $caTotal = CajaEntrada::where('caja_id', $this->id)
            ->where('estado', 1)
            ->sum('monto');

        // Sumar solo las salidas activas (estado = 1)
        $csTotal = CajaSalida::where('caja_id', $this->id)
            ->where('estado', 1)
            ->sum('monto');

        // Sumar solo las operaciones activas (estado = 1)
        $coTotal = CajaOperaciones::where('caja_id', $this->id)
            ->where('estado', 1)
            ->sum('monto');

        // Sumar solo las salidas de operaciones activas (estado = 1)
        $cosTotal = CajaSalidaOperaciones::where('caja_id', $this->id)
            ->where('estado', 1)
            ->sum('monto');
        $this->total_entrada = $caTotal;
        $this->total_salida = $csTotal;
        $this->total_operacion = $coTotal;
        $this->total_operacion_salida = $cosTotal;

        $this->save();
    }
}
