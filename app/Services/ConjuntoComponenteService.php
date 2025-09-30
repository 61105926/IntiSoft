<?php

namespace App\Services;

use App\Models\InstanciaConjunto;
use App\Models\InstanciaComponente;
use App\Models\AlquilerDetalleComponente;
use App\Models\HistorialComponentesConjunto;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Exception;

class ConjuntoComponenteService
{
    /**
     * Asigna componentes iniciales a una nueva instancia de conjunto
     */
    public function asignarComponentesIniciales(InstanciaConjunto $instancia, array $componentesData)
    {
        DB::beginTransaction();

        try {
            foreach ($componentesData as $data) {
                $instanciaComponente = InstanciaComponente::create([
                    'instancia_conjunto_id' => $instancia->id,
                    'componente_id' => $data['componente_id'],
                    'producto_id' => $data['producto_id'] ?? null,
                    'numero_serie_componente' => $data['numero_serie'] ?? $this->generarNumeroSerie($data['componente_id']),
                    'estado_fisico' => $data['estado_fisico'] ?? 'EXCELENTE',
                    'estado_actual' => 'ASIGNADO',
                    'fecha_asignacion' => now(),
                    'observaciones' => $data['observaciones'] ?? null,
                    'usuario_asignacion' => auth()->id()
                ]);

                HistorialComponentesConjunto::registrarAsignacionInicial(
                    $instancia->id,
                    $data['componente_id'],
                    $instanciaComponente->id,
                    $data['producto_id'] ?? null
                );
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Registra la devolución de componentes de un alquiler
     */
    public function registrarDevolucion($alquilerDetalleId, array $componentesDevolucion)
    {
        DB::beginTransaction();

        try {
            foreach ($componentesDevolucion as $devolucion) {
                $detalleComponente = AlquilerDetalleComponente::find($devolucion['id']);

                if (!$detalleComponente) {
                    continue;
                }

                $instanciaComponente = $detalleComponente->instanciaComponente;

                switch ($devolucion['estado']) {
                    case 'DEVUELTO':
                        $detalleComponente->marcarComoDevuelto(
                            $devolucion['estado_fisico'] ?? 'BUENO',
                            $devolucion['observaciones'] ?? null
                        );

                        $instanciaComponente->update([
                            'estado_fisico' => $devolucion['estado_fisico'] ?? 'BUENO'
                        ]);
                        break;

                    case 'PERDIDO':
                        $detalleComponente->marcarComoPerdido(
                            $devolucion['costo_penalizacion'] ?? 0,
                            $devolucion['observaciones'] ?? 'No devuelto por cliente'
                        );

                        $instanciaComponente->update([
                            'estado_actual' => 'PERDIDO',
                            'fecha_desvinculacion' => now()
                        ]);

                        HistorialComponentesConjunto::registrarPerdida(
                            $instanciaComponente->instancia_conjunto_id,
                            $instanciaComponente->componente_id,
                            $instanciaComponente->id,
                            $alquilerDetalleId,
                            $devolucion['observaciones'] ?? 'No devuelto por cliente'
                        );
                        break;

                    case 'DANADO':
                        $detalleComponente->marcarComoDanado(
                            $devolucion['costo_penalizacion'] ?? 0,
                            $devolucion['estado_fisico'] ?? 'MALO',
                            $devolucion['observaciones'] ?? null
                        );

                        $instanciaComponente->update([
                            'estado_actual' => 'DANADO',
                            'estado_fisico' => $devolucion['estado_fisico'] ?? 'MALO',
                            'fecha_desvinculacion' => now()
                        ]);

                        HistorialComponentesConjunto::create([
                            'instancia_conjunto_id' => $instanciaComponente->instancia_conjunto_id,
                            'componente_id' => $instanciaComponente->componente_id,
                            'instancia_componente_id' => $instanciaComponente->id,
                            'tipo_movimiento' => 'DANO',
                            'alquiler_detalle_id' => $alquilerDetalleId,
                            'motivo' => $devolucion['observaciones'] ?? 'Daño reportado',
                            'costo_reposicion' => $devolucion['costo_penalizacion'] ?? 0,
                            'fecha_movimiento' => now(),
                            'usuario_registro' => auth()->id(),
                            'created_at' => now()
                        ]);
                        break;
                }
            }

            // Actualizar estado de disponibilidad de la instancia
            $instancia = InstanciaConjunto::find($componentesDevolucion[0]['instancia_conjunto_id'] ?? null);
            if ($instancia) {
                $instancia->actualizarEstadoDisponibilidad();
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Repone un componente perdido o dañado
     */
    public function reponerComponente(
        InstanciaConjunto $instancia,
        $componenteId,
        $productoNuevoId,
        $costoReposicion,
        $motivo = null
    ) {
        DB::beginTransaction();

        try {
            $nuevoComponente = InstanciaComponente::create([
                'instancia_conjunto_id' => $instancia->id,
                'componente_id' => $componenteId,
                'producto_id' => $productoNuevoId,
                'numero_serie_componente' => $this->generarNumeroSerie($componenteId),
                'estado_fisico' => 'EXCELENTE',
                'estado_actual' => 'ASIGNADO',
                'fecha_asignacion' => now(),
                'observaciones' => $motivo ?? 'Reposición',
                'usuario_asignacion' => auth()->id()
            ]);

            HistorialComponentesConjunto::registrarReposicion(
                $instancia->id,
                $componenteId,
                $nuevoComponente->id,
                $productoNuevoId,
                $costoReposicion,
                $motivo
            );

            // Actualizar estado de disponibilidad
            $instancia->actualizarEstadoDisponibilidad();

            DB::commit();
            return $nuevoComponente;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reemplaza un componente existente por otro
     */
    public function reemplazarComponente(
        InstanciaComponente $componenteAnterior,
        $productoNuevoId,
        $costoReposicion = 0,
        $motivo = null
    ) {
        DB::beginTransaction();

        try {
            $componenteAnterior->update([
                'estado_actual' => 'REEMPLAZADO',
                'fecha_desvinculacion' => now()
            ]);

            $nuevoComponente = InstanciaComponente::create([
                'instancia_conjunto_id' => $componenteAnterior->instancia_conjunto_id,
                'componente_id' => $componenteAnterior->componente_id,
                'producto_id' => $productoNuevoId,
                'numero_serie_componente' => $this->generarNumeroSerie($componenteAnterior->componente_id),
                'estado_fisico' => 'EXCELENTE',
                'estado_actual' => 'ASIGNADO',
                'fecha_asignacion' => now(),
                'observaciones' => $motivo ?? 'Reemplazo',
                'usuario_asignacion' => auth()->id()
            ]);

            HistorialComponentesConjunto::registrarReemplazo(
                $componenteAnterior->instancia_conjunto_id,
                $componenteAnterior->componente_id,
                $componenteAnterior->producto_id,
                $productoNuevoId,
                $costoReposicion,
                $motivo
            );

            DB::commit();
            return $nuevoComponente;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Obtiene el resumen de componentes de una instancia
     */
    public function obtenerResumenComponentes(InstanciaConjunto $instancia)
    {
        return [
            'total_componentes' => $instancia->instanciaComponentes()->count(),
            'componentes_activos' => $instancia->componentesActivos()->count(),
            'componentes_perdidos' => $instancia->componentesPerdidos()->count(),
            'componentes_danados' => $instancia->componentesDanados()->count(),
            'esta_completo' => $instancia->estaCompleto(),
            'componentes_requeridos' => $instancia->variacionConjunto->conjunto->componentes()
                ->where('es_obligatorio', true)
                ->count()
        ];
    }

    /**
     * Genera un número de serie único para un componente
     */
    private function generarNumeroSerie($componenteId)
    {
        return 'COMP-' . $componenteId . '-' . strtoupper(uniqid());
    }

    /**
     * Obtiene estadísticas de pérdidas y reposiciones
     */
    public function obtenerEstadisticasPerdidas($fechaInicio = null, $fechaFin = null)
    {
        $query = HistorialComponentesConjunto::query();

        if ($fechaInicio) {
            $query->where('fecha_movimiento', '>=', $fechaInicio);
        }

        if ($fechaFin) {
            $query->where('fecha_movimiento', '<=', $fechaFin);
        }

        return [
            'total_perdidas' => $query->clone()->perdidas()->count(),
            'total_reposiciones' => $query->clone()->reposiciones()->count(),
            'costo_total_reposiciones' => $query->clone()->reposiciones()->sum('costo_reposicion'),
            'componentes_mas_perdidos' => $query->clone()
                ->perdidas()
                ->select('componente_id', DB::raw('count(*) as total'))
                ->groupBy('componente_id')
                ->orderByDesc('total')
                ->limit(10)
                ->with('componente')
                ->get()
        ];
    }
}
