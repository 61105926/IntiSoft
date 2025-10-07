<?php

namespace App\Observers;

use App\Models\InstanciaConjunto;
use App\Models\InstanciaComponente;

class InstanciaConjuntoObserver
{
    /**
     * Handle the InstanciaConjunto "created" event.
     * Automáticamente crea las instancias de componentes cuando se crea una instancia de conjunto
     */
    public function created(InstanciaConjunto $instanciaConjunto): void
    {
        // Obtener los componentes del conjunto
        $conjunto = $instanciaConjunto->variacionConjunto->conjunto;
        $componentesCatalogo = $conjunto->componentes;

        if ($componentesCatalogo->count() === 0) {
            \Log::warning('Instancia de conjunto creada sin componentes en catálogo', [
                'instancia_id' => $instanciaConjunto->id,
                'conjunto' => $conjunto->nombre
            ]);
            return;
        }

        // Crear una instancia de componente por cada componente del catálogo
        $contador = 0;
        foreach ($componentesCatalogo as $componente) {
            try {
                InstanciaComponente::create([
                    'instancia_conjunto_id' => $instanciaConjunto->id,
                    'componente_id' => $componente->id,
                    'numero_serie_componente' => $instanciaConjunto->numero_serie . '-C' . str_pad(++$contador, 2, '0', STR_PAD_LEFT),
                    'estado_actual' => 'ASIGNADO',
                    'estado_fisico' => 'BUENO',
                    'fecha_asignacion' => now(),
                    'observaciones' => 'Asignado automáticamente al crear instancia'
                ]);
            } catch (\Exception $e) {
                \Log::error('Error al crear instancia de componente', [
                    'instancia_conjunto_id' => $instanciaConjunto->id,
                    'componente_id' => $componente->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        \Log::info('Componentes creados para instancia de conjunto', [
            'instancia_id' => $instanciaConjunto->id,
            'conjunto' => $conjunto->nombre,
            'componentes_creados' => $contador
        ]);
    }

    /**
     * Handle the InstanciaConjunto "updated" event.
     */
    public function updated(InstanciaConjunto $instanciaConjunto): void
    {
        //
    }

    /**
     * Handle the InstanciaConjunto "deleted" event.
     */
    public function deleted(InstanciaConjunto $instanciaConjunto): void
    {
        //
    }

    /**
     * Handle the InstanciaConjunto "restored" event.
     */
    public function restored(InstanciaConjunto $instanciaConjunto): void
    {
        //
    }

    /**
     * Handle the InstanciaConjunto "force deleted" event.
     */
    public function forceDeleted(InstanciaConjunto $instanciaConjunto): void
    {
        //
    }
}
