<?php

namespace App\Traits;

trait CalculosFinancieros
{
    /**
     * Calcular subtotal basado en detalles
     */
    public function calcularSubtotal()
    {
        if ($this->relationLoaded('detalles') && $this->detalles->isNotEmpty()) {
            return $this->detalles->sum('subtotal');
        }
        return 0;
    }

    /**
     * Calcular total final con todos los componentes
     */
    public function calcularTotal()
    {
        $subtotal = $this->subtotal ?? $this->calcularSubtotal();
        $descuento = $this->descuento ?? 0;
        $impuestos = $this->impuestos ?? 0;
        $costos_adicionales = $this->costos_adicionales ?? 0;
        $penalizacion = $this->penalizacion ?? 0;

        return $subtotal - $descuento + $impuestos + $costos_adicionales + $penalizacion;
    }

    /**
     * Calcular saldo pendiente
     */
    public function calcularSaldoPendiente()
    {
        $total = $this->total ?? $this->calcularTotal();
        $anticipo = $this->anticipo ?? 0;
        $anticipo_reserva = $this->anticipo_reserva ?? 0;

        return $total - $anticipo - $anticipo_reserva;
    }

    /**
     * Actualizar todos los cálculos financieros
     */
    public function actualizarCalculosFinancieros()
    {
        $this->subtotal = $this->calcularSubtotal();
        $this->total = $this->calcularTotal();
        // No guardamos saldo_pendiente como campo, es un accessor
        $this->save();
        
        return $this;
    }

    /**
     * Aplicar descuento
     */
    public function aplicarDescuento($monto, $motivo = null)
    {
        $this->descuento += $monto;
        
        if ($motivo) {
            $this->observaciones = $this->observaciones . " | Descuento: $motivo ($" . number_format($monto, 2) . ")";
        }
        
        $this->actualizarCalculosFinancieros();
        return $this;
    }

    /**
     * Aplicar costo adicional
     */
    public function aplicarCostoAdicional($monto, $detalle = null)
    {
        $this->costos_adicionales += $monto;
        
        if ($detalle) {
            $detalles_actuales = $this->detalle_costos_adicionales ? 
                json_decode($this->detalle_costos_adicionales, true) : [];
            
            $detalles_actuales[] = [
                'concepto' => $detalle,
                'monto' => $monto,
                'fecha' => now()->toDateTimeString()
            ];
            
            $this->detalle_costos_adicionales = json_encode($detalles_actuales);
        }
        
        $this->actualizarCalculosFinancieros();
        return $this;
    }

    /**
     * Registrar pago adicional
     */
    public function registrarPago($monto, $metodo = 'efectivo', $referencia = null)
    {
        $this->anticipo += $monto;
        
        $detalle_pago = "Pago $metodo: $" . number_format($monto, 2);
        if ($referencia) {
            $detalle_pago .= " (Ref: $referencia)";
        }
        
        $this->observaciones = $this->observaciones . " | $detalle_pago";
        $this->actualizarCalculosFinancieros();
        
        // Actualizar estado de pago si es necesario
        if (method_exists($this, 'actualizarEstadoPago')) {
            $this->actualizarEstadoPago();
        }
        
        return $this;
    }

    /**
     * Obtener resumen financiero
     */
    public function getResumenFinanciero()
    {
        return [
            'subtotal' => $this->subtotal ?? 0,
            'descuento' => $this->descuento ?? 0,
            'impuestos' => $this->impuestos ?? 0,
            'costos_adicionales' => $this->costos_adicionales ?? 0,
            'penalizacion' => $this->penalizacion ?? 0,
            'total' => $this->total ?? 0,
            'anticipo' => $this->anticipo ?? 0,
            'anticipo_reserva' => $this->anticipo_reserva ?? 0,
            'saldo_pendiente' => $this->saldo_pendiente ?? 0,
            'porcentaje_pagado' => $this->total > 0 ? 
                (($this->anticipo + ($this->anticipo_reserva ?? 0)) / $this->total) * 100 : 0
        ];
    }

    /**
     * Verificar si está completamente pagado
     */
    public function estaCompletamentePagado()
    {
        return $this->calcularSaldoPendiente() <= 0;
    }

    /**
     * Verificar si tiene saldo pendiente
     */
    public function tieneSaldoPendiente()
    {
        return $this->calcularSaldoPendiente() > 0;
    }
}