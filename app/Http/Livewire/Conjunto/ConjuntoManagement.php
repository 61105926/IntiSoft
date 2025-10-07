<?php

namespace App\Http\Livewire\Conjunto;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Conjunto;
use App\Models\CategoriaConjunto;
use App\Models\VariacionConjunto;
use App\Models\InstanciaConjunto;
use Illuminate\Support\Facades\DB;

class ConjuntoManagement extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    // Filtros
    public $searchTerm = '';
    public $filterCategoria = '';
    public $filterGenero = '';
    public $filterDisponibilidad = '';
    public $sortBy = 'nombre';
    public $vistaActual = 'tarjetas';

    // Modal states
    public $showNewConjuntoModal = false;
    public $showViewConjuntoModal = false;
    public $showManageInstancesModal = false;

    // Selected conjunto
    public $selectedConjunto = null;

    // Nuevo conjunto data
    public $newConjunto = [
        'codigo' => '',
        'nombre' => '',
        'categoria_conjunto_id' => '',
        'descripcion' => '',
        'precio_venta_base' => 0,
        'precio_alquiler_dia' => 0,
        'precio_alquiler_semana' => 0,
        'precio_alquiler_mes' => 0,
        'genero' => 'UNISEX',
        'temporada' => 'TODO_ANIO',
        'disponible_venta' => true,
        'disponible_alquiler' => true,
        'requiere_limpieza' => true,
        'tiempo_limpieza_horas' => 24,
        'peso_aproximado' => 0,
        'observaciones' => '',
    ];

    public $componentesSeleccionados = [];
    public $variaciones = [];
    public $componentes = []; // Cambiado de tiposComponente a componentes

    // Formulario para crear instancias
    public $instanceForm = [
        'variacion_id' => '',
        'cantidad' => 1,
        'sucursal_id' => '',
        'prefijo_serie' => 'INST',
        'lote_fabricacion' => '',
        'estado_fisico' => 'EXCELENTE',
        'observaciones' => '',
    ];

    public $disponibilidadVerificada = null;

    public function mount()
    {
        // Cargar los componentes específicos con su tipo de componente
        $this->componentes = \App\Models\Componente::with('tipoComponente')
            ->where('activo', true)
            ->orderBy('tipo_componente_id')
            ->orderBy('nombre')
            ->get();
    }

    public function render()
    {
        $conjuntos = $this->getFilteredConjuntos();
        $categorias = CategoriaConjunto::where('activo', true)->orderBy('orden_visualizacion')->get();
        $estadisticas = $this->getEstadisticas();
        $sucursales = \App\Models\Sucursal::where('activo', true)->orderBy('nombre')->get();

        return view('livewire.conjunto.conjunto-management', [
            'conjuntos' => $conjuntos,
            'categorias' => $categorias,
            'estadisticas' => $estadisticas,
            'sucursales' => $sucursales,
        ])->extends('layouts.theme.modern-app')->section('content');
    }

    private function getFilteredConjuntos()
    {
        $query = Conjunto::with(['categoriaConjunto', 'variaciones.instancias'])
            ->withCount([
                'variaciones',
                'variaciones as instancias_count' => function ($query) {
                    $query->join('instancias_conjunto', 'variaciones_conjunto.id', '=', 'instancias_conjunto.variacion_conjunto_id');
                }
            ]);

        // Aplicar filtros
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('codigo', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('descripcion', 'like', '%' . $this->searchTerm . '%');
            });
        }

        if ($this->filterCategoria) {
            $query->where('categoria_conjunto_id', $this->filterCategoria);
        }

        if ($this->filterGenero) {
            $query->where('genero', $this->filterGenero);
        }

        // Ordenamiento
        switch ($this->sortBy) {
            case 'codigo':
                $query->orderBy('codigo');
                break;
            case 'categoria':
                $query->join('categorias_conjunto', 'conjuntos.categoria_conjunto_id', '=', 'categorias_conjunto.id')
                      ->orderBy('categorias_conjunto.nombre');
                break;
            case 'precio_alquiler_dia':
                $query->orderBy('precio_alquiler_dia', 'desc');
                break;
            case 'created_at':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('nombre');
                break;
        }

        return $query->where('activo', true)->paginate(12);
    }

    private function getEstadisticas()
    {
        $totalConjuntos = Conjunto::where('activo', true)->count();

        $totalInstancias = InstanciaConjunto::where('activa', true)->count();

        $disponibles = InstanciaConjunto::where('activa', true)
            ->where('estado_disponibilidad', 'DISPONIBLE')
            ->count();

        $enUso = InstanciaConjunto::where('activa', true)
            ->whereIn('estado_disponibilidad', ['ALQUILADO', 'RESERVADO'])
            ->count();

        $porcentajeDisponibles = $totalInstancias > 0 ? ($disponibles / $totalInstancias) * 100 : 0;

        // Calcular ROI promedio (simplificado)
        $roiPromedio = 85.6; // Placeholder, sería calculado basado en ingresos vs costo

        // Valor de inventario (simplificado)
        $valorInventario = Conjunto::where('activo', true)->sum('precio_venta_base') / 1000; // En miles

        return [
            'total_conjuntos' => $totalConjuntos,
            'total_instancias' => $totalInstancias,
            'disponibles' => $disponibles,
            'en_uso' => $enUso,
            'porcentaje_disponibles' => $porcentajeDisponibles,
            'roi_promedio' => $roiPromedio,
            'valor_inventario' => $valorInventario,
        ];
    }

    public function getCategoryBadgeColor($categoriaId)
    {
        $colors = [
            1 => 'primary',   // TRAJES_FOLKLORICOS
            2 => 'success',   // CONJUNTOS_DANZA
            3 => 'warning',   // CONJUNTOS_ENSAYO
            4 => 'info',      // CONJUNTOS_INFANTILES
            5 => 'danger',    // CONJUNTOS_PREMIUM
        ];

        return $colors[$categoriaId] ?? 'secondary';
    }

    // Métodos para filtros
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedFilterCategoria()
    {
        $this->resetPage();
    }

    public function updatedFilterGenero()
    {
        $this->resetPage();
    }

    public function updatedFilterDisponibilidad()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    // Métodos para modales
    public function openNewConjuntoModal()
    {
        $this->showNewConjuntoModal = true;
    }

    public function closeNewConjuntoModal()
    {
        $this->showNewConjuntoModal = false;
    }

    public function viewConjunto($conjuntoId)
    {
        $this->selectedConjunto = Conjunto::with(['categoriaConjunto', 'variaciones.instancias', 'componentes'])
            ->find($conjuntoId);
        $this->showViewConjuntoModal = true;
    }

    public function editConjunto($conjuntoId)
    {
        // Implementar edición
        session()->flash('info', 'Función de edición en desarrollo');
    }

    public function duplicateConjunto($conjuntoId)
    {
        // Implementar duplicación
        session()->flash('info', 'Función de duplicación en desarrollo');
    }

    public function deleteConjunto($conjuntoId)
    {
        // Implementar eliminación
        session()->flash('info', 'Función de eliminación en desarrollo');
    }

    public function manageInstances($conjuntoId)
    {
        $this->selectedConjunto = Conjunto::with(['variaciones.instancias'])->find($conjuntoId);
        $this->showManageInstancesModal = true;
    }

    public function viewStats($conjuntoId)
    {
        session()->flash('info', 'Vista de estadísticas en desarrollo');
    }

    public function viewHistory($conjuntoId)
    {
        session()->flash('info', 'Vista de historial en desarrollo');
    }

    public function exportData()
    {
        session()->flash('info', 'Función de exportación en desarrollo');
    }

    public function refreshData()
    {
        $this->resetPage();
        session()->flash('success', 'Datos actualizados correctamente');
    }

    public function clearFilters()
    {
        $this->searchTerm = '';
        $this->filterCategoria = '';
        $this->filterGenero = '';
        $this->filterDisponibilidad = '';
        $this->resetPage();
    }

    // Métodos para manejo de variaciones
    public function agregarVariacion()
    {
        $this->variaciones[] = [
            'talla' => '',
            'color' => '',
            'estilo' => '',
            'precio_venta' => $this->newConjunto['precio_venta_base'],
            'precio_alquiler_dia' => $this->newConjunto['precio_alquiler_dia']
        ];
    }

    public function eliminarVariacion($index)
    {
        unset($this->variaciones[$index]);
        $this->variaciones = array_values($this->variaciones);
    }

    public function actualizarVariacion($index, $campo, $valor)
    {
        if (isset($this->variaciones[$index])) {
            $this->variaciones[$index][$campo] = $valor;
        }
    }

    // Validación y guardado
    public function puedeGuardarConjunto()
    {
        return !empty($this->newConjunto['codigo']) &&
               !empty($this->newConjunto['nombre']) &&
               !empty($this->newConjunto['categoria_conjunto_id']) &&
               count($this->componentesSeleccionados) > 0 &&
               ($this->newConjunto['disponible_venta'] || $this->newConjunto['disponible_alquiler']);
    }

    public function guardarConjunto()
    {
        try {
            // Validación mejorada con mensajes específicos
            if (empty($this->newConjunto['codigo'])) {
                session()->flash('error', 'El código del conjunto es obligatorio.');
                return;
            }

            if (empty($this->newConjunto['nombre'])) {
                session()->flash('error', 'El nombre del conjunto es obligatorio.');
                return;
            }

            if (empty($this->newConjunto['categoria_conjunto_id'])) {
                session()->flash('error', 'Debe seleccionar una categoría.');
                return;
            }

            if (count($this->componentesSeleccionados) === 0) {
                session()->flash('error', 'Debe seleccionar al menos un componente para el conjunto.');
                return;
            }

            if (!$this->newConjunto['disponible_venta'] && !$this->newConjunto['disponible_alquiler']) {
                session()->flash('error', 'El conjunto debe estar disponible para venta o alquiler.');
                return;
            }

            DB::beginTransaction();

            // Crear el conjunto principal
            $conjunto = Conjunto::create([
                'codigo' => $this->newConjunto['codigo'],
                'nombre' => $this->newConjunto['nombre'],
                'categoria_conjunto_id' => $this->newConjunto['categoria_conjunto_id'],
                'descripcion' => $this->newConjunto['descripcion'],
                'precio_venta_base' => $this->newConjunto['precio_venta_base'],
                'precio_alquiler_dia' => $this->newConjunto['precio_alquiler_dia'],
                'precio_alquiler_semana' => $this->newConjunto['precio_alquiler_semana'],
                'precio_alquiler_mes' => $this->newConjunto['precio_alquiler_mes'],
                'genero' => $this->newConjunto['genero'],
                'temporada' => $this->newConjunto['temporada'],
                'disponible_venta' => $this->newConjunto['disponible_venta'],
                'disponible_alquiler' => $this->newConjunto['disponible_alquiler'],
                'requiere_limpieza' => $this->newConjunto['requiere_limpieza'],
                'tiempo_limpieza_horas' => $this->newConjunto['tiempo_limpieza_horas'],
                'peso_aproximado' => $this->newConjunto['peso_aproximado'],
                'observaciones' => $this->newConjunto['observaciones'],
                'usuario_creacion' => auth()->id(),
                'activo' => true,
            ]);

            // Asociar componentes seleccionados
            if (!empty($this->componentesSeleccionados)) {
                $conjunto->componentes()->attach($this->componentesSeleccionados);
            }

            // Crear variaciones
            $contadorVariaciones = 1;
            foreach ($this->variaciones as $variacionData) {
                if (!empty($variacionData['talla']) || !empty($variacionData['color']) || !empty($variacionData['estilo'])) {
                    // Generar código de variación automáticamente
                    $codigoVariacion = $conjunto->codigo . '-VAR-' . str_pad($contadorVariaciones, 3, '0', STR_PAD_LEFT);

                    // Generar nombre de variación
                    $nombreVariacion = $conjunto->nombre;
                    $partes = [];
                    if (!empty($variacionData['color'])) $partes[] = $variacionData['color'];
                    if (!empty($variacionData['talla'])) $partes[] = $variacionData['talla'];
                    if (!empty($variacionData['estilo'])) $partes[] = $variacionData['estilo'];
                    if (count($partes) > 0) {
                        $nombreVariacion .= ' - ' . implode(' ', $partes);
                    }

                    VariacionConjunto::create([
                        'conjunto_id' => $conjunto->id,
                        'codigo_variacion' => $codigoVariacion,
                        'nombre_variacion' => $nombreVariacion,
                        'talla' => $variacionData['talla'] ?? null,
                        'color' => $variacionData['color'] ?? null,
                        'estilo' => $variacionData['estilo'] ?? null,
                        'precio_venta' => $variacionData['precio_venta'] ?? $conjunto->precio_venta_base,
                        'precio_alquiler_dia' => $variacionData['precio_alquiler_dia'] ?? $conjunto->precio_alquiler_dia,
                        'usuario_creacion' => auth()->id(),
                        'activa' => true,
                    ]);

                    $contadorVariaciones++;
                }
            }

            DB::commit();

            // Limpiar formulario
            $this->resetNewConjuntoForm();
            $this->showNewConjuntoModal = false;

            session()->flash('success', 'Conjunto creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error al crear conjunto', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $this->newConjunto
            ]);
            session()->flash('error', 'Error al crear el conjunto: ' . $e->getMessage());
        }
    }

    private function resetNewConjuntoForm()
    {
        $this->newConjunto = [
            'codigo' => '',
            'nombre' => '',
            'categoria_conjunto_id' => '',
            'descripcion' => '',
            'precio_venta_base' => 0,
            'precio_alquiler_dia' => 0,
            'precio_alquiler_semana' => 0,
            'precio_alquiler_mes' => 0,
            'genero' => 'UNISEX',
            'temporada' => 'TODO_ANIO',
            'disponible_venta' => true,
            'disponible_alquiler' => true,
            'requiere_limpieza' => true,
            'tiempo_limpieza_horas' => 24,
            'peso_aproximado' => 0,
            'observaciones' => '',
        ];
        $this->componentesSeleccionados = [];
        $this->variaciones = [];
    }

    // Métodos para gestión de instancias
    public function verificarDisponibilidad()
    {
        if (!$this->instanceForm['variacion_id'] || !$this->instanceForm['sucursal_id']) {
            return;
        }

        // Simulación de verificación de disponibilidad
        $this->disponibilidadVerificada = [
            'disponible' => true,
            'cantidad_maxima' => 50,
            'componentes_limitantes' => [],
        ];
    }

    public function puedeCrearInstancias()
    {
        return !empty($this->instanceForm['variacion_id']) &&
               !empty($this->instanceForm['sucursal_id']) &&
               $this->instanceForm['cantidad'] > 0 &&
               !empty($this->instanceForm['prefijo_serie']);
    }

    public function crearInstanciasMasivas()
    {
        if (!$this->puedeCrearInstancias()) {
            session()->flash('error', 'Por favor complete todos los campos requeridos.');
            return;
        }

        try {
            DB::beginTransaction();

            // Obtener la variación para generar códigos adecuados
            $variacion = VariacionConjunto::with('conjunto')->find($this->instanceForm['variacion_id']);
            if (!$variacion) {
                throw new \Exception('Variación no encontrada');
            }

            // Contar cuántas instancias ya existen de esta variación para continuar la numeración
            $instanciasExistentes = InstanciaConjunto::where('variacion_conjunto_id', $variacion->id)->count();
            $contadorInicial = $instanciasExistentes + 1;

            $instanciasCreadas = [];

            for ($i = 0; $i < $this->instanceForm['cantidad']; $i++) {
                $numeroSecuencial = $contadorInicial + $i;

                $instancia = InstanciaConjunto::create([
                    'variacion_conjunto_id' => $this->instanceForm['variacion_id'],
                    'numero_serie' => $this->instanceForm['prefijo_serie'] . '-' . str_pad($numeroSecuencial, 3, '0', STR_PAD_LEFT),
                    'codigo_interno' => 'INT-' . $variacion->codigo_variacion . '-' . str_pad($numeroSecuencial, 3, '0', STR_PAD_LEFT),
                    'sucursal_id' => $this->instanceForm['sucursal_id'],
                    'estado_fisico' => $this->instanceForm['estado_fisico'],
                    'estado_disponibilidad' => 'DISPONIBLE',
                    'fecha_adquisicion' => now(),
                    'total_usos' => 0,
                    'total_ingresos' => 0,
                    'ubicacion_almacen' => 'ESTANTE-A-' . rand(1, 10),
                    'lote_fabricacion' => $this->instanceForm['lote_fabricacion'] ?? '',
                    'observaciones' => $this->instanceForm['observaciones'] ?? '',
                    'usuario_creacion' => auth()->id(),
                    'activa' => true,
                ]);

                $instanciasCreadas[] = $instancia;
            }

            DB::commit();

            \Log::info('Instancias creadas masivamente', [
                'variacion' => $variacion->nombre_variacion,
                'cantidad' => count($instanciasCreadas),
                'usuario' => auth()->user()->name ?? auth()->id()
            ]);

            // Limpiar formulario
            $this->resetInstanceForm();
            $this->showManageInstancesModal = false;

            session()->flash('success', count($instanciasCreadas) . ' instancia(s) de "' . $variacion->nombre_variacion . '" creadas exitosamente con sus componentes.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error al crear instancias masivas', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'form_data' => $this->instanceForm
            ]);
            session()->flash('error', 'Error al crear las instancias: ' . $e->getMessage());
        }
    }

    private function resetInstanceForm()
    {
        $this->instanceForm = [
            'variacion_id' => '',
            'cantidad' => 1,
            'sucursal_id' => '',
            'prefijo_serie' => 'INST',
            'lote_fabricacion' => '',
            'estado_fisico' => 'EXCELENTE',
            'observaciones' => '',
        ];
        $this->disponibilidadVerificada = null;
    }
}
