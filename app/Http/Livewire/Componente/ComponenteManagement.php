<?php

namespace App\Http\Livewire\Componente;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Componente;
use App\Models\TipoComponente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ComponenteManagement extends Component
{
    use WithPagination, WithFileUploads;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    // Filtros
    public $searchTerm = '';
    public $filterTipo = '';
    public $filterGenero = '';
    public $filterActivo = '';
    public $sortBy = 'nombre';
    public $vistaActual = 'tabla';

    // Modal states
    public $showNewComponenteModal = false;
    public $showEditComponenteModal = false;
    public $showViewComponenteModal = false;

    // Selected componente
    public $selectedComponente = null;
    public $componenteId = null;

    // Form data
    public $form = [
        'codigo' => '',
        'nombre' => '',
        'descripcion' => '',
        'tipo_componente_id' => '',
        'genero' => 'UNISEX',
        'talla' => '',
        'color' => '',
        'material' => '',
        'peso' => 0,
        'costo_unitario' => 0,
        'precio_venta_individual' => 0,
        'precio_alquiler_individual' => 0,
        'es_reutilizable' => true,
        'requiere_limpieza' => true,
        'tiempo_limpieza_horas' => 24,
        'vida_util_usos' => 100,
        'observaciones' => '',
        'activo' => true,
    ];

    public $imagen;
    public $imagenPreview = null;

    protected $rules = [
        'form.codigo' => 'required|unique:componentes,codigo',
        'form.nombre' => 'required|min:3',
        'form.tipo_componente_id' => 'required|exists:tipos_componente,id',
        'form.genero' => 'required|in:MASCULINO,FEMENINO,UNISEX,INFANTIL',
        'form.costo_unitario' => 'nullable|numeric|min:0',
        'form.precio_venta_individual' => 'nullable|numeric|min:0',
        'form.precio_alquiler_individual' => 'nullable|numeric|min:0',
        'imagen' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->generateCodigo();
    }

    public function render()
    {
        $componentes = $this->getFilteredComponentes();
        $tiposComponente = TipoComponente::where('activo', true)
            ->orderBy('orden_visualizacion')
            ->get();
        $estadisticas = $this->getEstadisticas();

        return view('livewire.componente.componente-management', [
            'componentes' => $componentes,
            'tiposComponente' => $tiposComponente,
            'estadisticas' => $estadisticas,
        ])->extends('layouts.theme.modern-app')->section('content');
    }

    private function getFilteredComponentes()
    {
        $query = Componente::with(['tipoComponente', 'conjuntos']);

        // Aplicar filtros
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('codigo', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('descripcion', 'like', '%' . $this->searchTerm . '%');
            });
        }

        if ($this->filterTipo) {
            $query->where('tipo_componente_id', $this->filterTipo);
        }

        if ($this->filterGenero) {
            $query->where('genero', $this->filterGenero);
        }

        if ($this->filterActivo !== '') {
            $query->where('activo', $this->filterActivo);
        }

        // Ordenamiento
        switch ($this->sortBy) {
            case 'codigo':
                $query->orderBy('codigo');
                break;
            case 'tipo':
                $query->join('tipos_componente', 'componentes.tipo_componente_id', '=', 'tipos_componente.id')
                      ->orderBy('tipos_componente.nombre');
                break;
            case 'precio':
                $query->orderBy('precio_venta_individual', 'desc');
                break;
            case 'created_at':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('nombre');
                break;
        }

        return $query->paginate(15);
    }

    private function getEstadisticas()
    {
        $totalComponentes = Componente::where('activo', true)->count();
        $totalTipos = TipoComponente::where('activo', true)->count();

        $porGenero = Componente::where('activo', true)
            ->select('genero', DB::raw('count(*) as total'))
            ->groupBy('genero')
            ->get()
            ->pluck('total', 'genero')
            ->toArray();

        $valorInventario = Componente::where('activo', true)
            ->sum('precio_venta_individual');

        return [
            'total_componentes' => $totalComponentes,
            'total_tipos' => $totalTipos,
            'por_genero' => $porGenero,
            'valor_inventario' => $valorInventario,
            'femeninos' => $porGenero['FEMENINO'] ?? 0,
            'masculinos' => $porGenero['MASCULINO'] ?? 0,
            'unisex' => $porGenero['UNISEX'] ?? 0,
        ];
    }

    // Métodos para filtros
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedFilterTipo()
    {
        $this->resetPage();
    }

    public function updatedFilterGenero()
    {
        $this->resetPage();
    }

    public function updatedFilterActivo()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->searchTerm = '';
        $this->filterTipo = '';
        $this->filterGenero = '';
        $this->filterActivo = '';
        $this->resetPage();
    }

    // Métodos para modales
    public function openNewComponenteModal()
    {
        $this->resetForm();
        $this->generateCodigo();
        $this->showNewComponenteModal = true;
    }

    public function closeNewComponenteModal()
    {
        $this->showNewComponenteModal = false;
        $this->resetForm();
    }

    public function openEditComponenteModal($componenteId)
    {
        $this->componenteId = $componenteId;
        $componente = Componente::findOrFail($componenteId);

        $this->form = [
            'codigo' => $componente->codigo,
            'nombre' => $componente->nombre,
            'descripcion' => $componente->descripcion,
            'tipo_componente_id' => $componente->tipo_componente_id,
            'genero' => $componente->genero,
            'talla' => $componente->talla,
            'color' => $componente->color,
            'material' => $componente->material,
            'peso' => $componente->peso,
            'costo_unitario' => $componente->costo_unitario,
            'precio_venta_individual' => $componente->precio_venta_individual,
            'precio_alquiler_individual' => $componente->precio_alquiler_individual,
            'es_reutilizable' => $componente->es_reutilizable,
            'requiere_limpieza' => $componente->requiere_limpieza,
            'tiempo_limpieza_horas' => $componente->tiempo_limpieza_horas,
            'vida_util_usos' => $componente->vida_util_usos,
            'observaciones' => $componente->observaciones,
            'activo' => $componente->activo,
        ];

        $this->imagenPreview = $componente->imagen;
        $this->showEditComponenteModal = true;
    }

    public function closeEditComponenteModal()
    {
        $this->showEditComponenteModal = false;
        $this->resetForm();
    }

    public function viewComponente($componenteId)
    {
        $this->selectedComponente = Componente::with(['tipoComponente', 'conjuntos'])
            ->findOrFail($componenteId);
        $this->showViewComponenteModal = true;
    }

    public function closeViewComponenteModal()
    {
        $this->showViewComponenteModal = false;
        $this->selectedComponente = null;
    }

    // CRUD Operations
    public function saveComponente()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $data = $this->form;
            $data['usuario_creacion'] = auth()->id();

            // Manejar imagen
            if ($this->imagen) {
                $imagePath = $this->imagen->store('componentes', 'public');
                $data['imagen'] = $imagePath;
            }

            Componente::create($data);

            DB::commit();

            $this->closeNewComponenteModal();
            $this->resetForm();

            session()->flash('success', 'Componente creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error al crear el componente: ' . $e->getMessage());
        }
    }

    public function updateComponente()
    {
        $this->validate([
            'form.codigo' => 'required|unique:componentes,codigo,' . $this->componenteId,
            'form.nombre' => 'required|min:3',
            'form.tipo_componente_id' => 'required|exists:tipos_componente,id',
            'imagen' => 'nullable|image|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $componente = Componente::findOrFail($this->componenteId);
            $data = $this->form;

            // Manejar imagen
            if ($this->imagen) {
                // Eliminar imagen anterior si existe
                if ($componente->imagen) {
                    Storage::disk('public')->delete($componente->imagen);
                }
                $imagePath = $this->imagen->store('componentes', 'public');
                $data['imagen'] = $imagePath;
            }

            $componente->update($data);

            DB::commit();

            $this->closeEditComponenteModal();
            $this->resetForm();

            session()->flash('success', 'Componente actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error al actualizar el componente: ' . $e->getMessage());
        }
    }

    public function deleteComponente($componenteId)
    {
        try {
            $componente = Componente::findOrFail($componenteId);

            // Verificar si está en uso en conjuntos
            if ($componente->conjuntos()->count() > 0) {
                session()->flash('error', 'No se puede eliminar. El componente está asociado a conjuntos.');
                return;
            }

            // Eliminar imagen si existe
            if ($componente->imagen) {
                Storage::disk('public')->delete($componente->imagen);
            }

            $componente->delete();

            session()->flash('success', 'Componente eliminado exitosamente.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el componente: ' . $e->getMessage());
        }
    }

    public function toggleActivo($componenteId)
    {
        try {
            $componente = Componente::findOrFail($componenteId);
            $componente->activo = !$componente->activo;
            $componente->save();

            $estado = $componente->activo ? 'activado' : 'desactivado';
            session()->flash('success', "Componente {$estado} exitosamente.");

        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }

    public function duplicateComponente($componenteId)
    {
        try {
            $componente = Componente::findOrFail($componenteId);
            $newComponente = $componente->replicate();
            $newComponente->codigo = $this->generateUniqueCodigo($componente->codigo);
            $newComponente->nombre = $componente->nombre . ' (Copia)';
            $newComponente->usuario_creacion = auth()->id();
            $newComponente->save();

            session()->flash('success', 'Componente duplicado exitosamente.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al duplicar el componente: ' . $e->getMessage());
        }
    }

    // Helpers
    private function generateCodigo()
    {
        $lastComponente = Componente::orderBy('id', 'desc')->first();
        $nextNumber = $lastComponente ? $lastComponente->id + 1 : 1;
        $this->form['codigo'] = 'COMP-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    private function generateUniqueCodigo($baseCodigo)
    {
        $counter = 1;
        $newCodigo = $baseCodigo . '-' . $counter;

        while (Componente::where('codigo', $newCodigo)->exists()) {
            $counter++;
            $newCodigo = $baseCodigo . '-' . $counter;
        }

        return $newCodigo;
    }

    private function resetForm()
    {
        $this->form = [
            'codigo' => '',
            'nombre' => '',
            'descripcion' => '',
            'tipo_componente_id' => '',
            'genero' => 'UNISEX',
            'talla' => '',
            'color' => '',
            'material' => '',
            'peso' => 0,
            'costo_unitario' => 0,
            'precio_venta_individual' => 0,
            'precio_alquiler_individual' => 0,
            'es_reutilizable' => true,
            'requiere_limpieza' => true,
            'tiempo_limpieza_horas' => 24,
            'vida_util_usos' => 100,
            'observaciones' => '',
            'activo' => true,
        ];
        $this->imagen = null;
        $this->imagenPreview = null;
        $this->componenteId = null;
        $this->resetErrorBag();
    }

    public function exportData()
    {
        session()->flash('info', 'Función de exportación en desarrollo.');
    }

    public function refreshData()
    {
        $this->resetPage();
        session()->flash('success', 'Datos actualizados correctamente.');
    }
}