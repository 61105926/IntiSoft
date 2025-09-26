<?php

namespace App\Http\Livewire\EventoFolklorico;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EventoFolklorico;
use App\Models\EventoParticipante;
use App\Models\EventoVestimenta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\Garantia;
use App\Models\Caja;
use App\Models\MovimientoCaja;
use App\Models\StockPorSucursal;
use App\Models\HistorialProducto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventoFolkloricoController extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    // Filtros y búsqueda
    public $searchTerm = '';
    public $filterEstado = 'TODOS';
    public $filterTipo = 'TODOS';
    public $filterSucursal = 'TODAS';

    // Modal states
    public $showNewEventoModal = false;
    public $showViewEventoModal = false;
    public $showParticipanteModal = false;
    public $showVestimentaModal = false;
    public $showFinalizarEventoModal = false;

    // Form data para nuevo evento
    public $nombre_evento = '';
    public $descripcion = '';
    public $tipo_evento = 'FESTIVAL';
    public $institucion_organizadora = '';
    public $fecha_evento;
    public $hora_evento = '10:00';
    public $lugar_evento = '';
    public $direccion_evento = '';
    public $numero_participantes = 0;
    public $costo_por_participante = 0;
    public $requiere_transporte = false;
    public $observaciones = '';
    public $sucursal_id = '';

    // Participante data
    public $participante_cliente_id = '';
    public $participante_nombre = '';
    public $participante_cedula = '';
    public $participante_telefono = '';
    public $participante_email = '';
    public $participante_edad = '';
    public $participante_talla = 'M';
    public $participante_observaciones = '';
    public $participante_monto_garantia = 0;
    public $participante_monto_participacion = 0;

    // Asignación de vestimenta
    public $selectedParticipante = null;
    public $vestimentaProductos = [];
    public $currentProductId = '';

    // Evento seleccionado
    public $selectedEvento = null;

    protected $rules = [
        'nombre_evento' => 'required|string|max:255',
        'tipo_evento' => 'required|in:FESTIVAL,CONCURSO,PRESENTACION,DESFILE,ESCOLAR,UNIVERSITARIO',
        'fecha_evento' => 'required|date|after_or_equal:today',
        'lugar_evento' => 'required|string|max:255',
        'numero_participantes' => 'required|integer|min:1',
        'costo_por_participante' => 'required|numeric|min:0',
        'sucursal_id' => 'required|exists:sucursals,id',
    ];

    public function mount()
    {
        $this->fecha_evento = Carbon::now()->addDays(7)->format('Y-m-d');
        $this->sucursal_id = Auth::user()->sucursal_id ?? '';
    }

    public function render()
    {
        $eventos = $this->getFilteredEventos();
        $clientes = Cliente::orderBy('nombres')->get();
        $productos = Producto::where('activo', true)->orderBy('nombre')->get();
        $sucursales = Sucursal::orderBy('nombre')->get();
        $estadisticas = $this->getEstadisticas();
        $cajas = Caja::where('estado', 'ABIERTA')->orderBy('nombre')->get();

        return view('livewire.evento-folklorico.evento-folklorico', [
            'eventos' => $eventos,
            'clientes' => $clientes,
            'productos' => $productos,
            'sucursales' => $sucursales,
            'estadisticas' => $estadisticas,
            'cajas' => $cajas,
        ])->extends('layouts.theme.modern-app')->section('content');
    }

    private function getFilteredEventos()
    {
        $query = EventoFolklorico::with(['sucursal', 'usuarioCreacion', 'participantes']);

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('numero_evento', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('nombre_evento', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('institucion_organizadora', 'like', '%' . $this->searchTerm . '%');
            });
        }

        if ($this->filterEstado !== 'TODOS') {
            $query->where('estado', $this->filterEstado);
        }

        if ($this->filterTipo !== 'TODOS') {
            $query->where('tipo_evento', $this->filterTipo);
        }

        if ($this->filterSucursal !== 'TODAS') {
            $query->where('sucursal_id', $this->filterSucursal);
        }

        return $query->orderBy('fecha_evento', 'desc')->paginate(10);
    }

    private function getEstadisticas()
    {
        return [
            'total_eventos' => EventoFolklorico::count(),
            'eventos_activos' => EventoFolklorico::activos()->count(),
            'eventos_proximos' => EventoFolklorico::proximos()->count(),
            'participantes_totales' => EventoParticipante::confirmados()->count(),
            'ingresos_eventos' => EventoParticipante::pagados()->sum('monto_participacion'),
            'eventos_hoy' => EventoFolklorico::whereDate('fecha_evento', Carbon::today())->count(),
        ];
    }

    // Métodos para gestión de eventos
    public function openNewEventoModal()
    {
        $this->resetEventoForm();
        $this->showNewEventoModal = true;
    }

    public function closeNewEventoModal()
    {
        $this->showNewEventoModal = false;
        $this->resetEventoForm();
    }

    public function saveNewEvento()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $numeroEvento = $this->generarNumeroEvento();

            $evento = EventoFolklorico::create([
                'numero_evento' => $numeroEvento,
                'nombre_evento' => $this->nombre_evento,
                'descripcion' => $this->descripcion,
                'tipo_evento' => $this->tipo_evento,
                'institucion_organizadora' => $this->institucion_organizadora,
                'fecha_evento' => $this->fecha_evento,
                'hora_evento' => $this->hora_evento,
                'lugar_evento' => $this->lugar_evento,
                'direccion_evento' => $this->direccion_evento,
                'numero_participantes' => $this->numero_participantes,
                'costo_por_participante' => $this->costo_por_participante,
                'total_estimado' => $this->numero_participantes * $this->costo_por_participante,
                'requiere_transporte' => $this->requiere_transporte,
                'observaciones' => $this->observaciones,
                'sucursal_id' => $this->sucursal_id,
                'usuario_creacion_id' => Auth::id(),
                'estado' => EventoFolklorico::ESTADO_PLANIFICADO,
            ]);

            DB::commit();

            session()->flash('success', 'Evento folklórico creado exitosamente.');
            $this->closeNewEventoModal();

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error al crear el evento: ' . $e->getMessage());
        }
    }

    // Métodos para gestión de participantes
    public function openParticipanteModal($eventoId)
    {
        $this->selectedEvento = EventoFolklorico::find($eventoId);
        $this->resetParticipanteForm();
        $this->showParticipanteModal = true;
    }

    public function closeParticipanteModal()
    {
        $this->showParticipanteModal = false;
        $this->selectedEvento = null;
        $this->resetParticipanteForm();
    }

    public function registrarParticipante()
    {
        $this->validate([
            'participante_cliente_id' => 'required|exists:clientes,id',
            'participante_nombre' => 'required|string|max:255',
            'participante_talla' => 'required|in:XS,S,M,L,XL,XXL',
            'participante_monto_garantia' => 'required|numeric|min:0',
            'participante_monto_participacion' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Verificar cupos disponibles
            $participantesActuales = $this->selectedEvento->participantes()->confirmados()->count();
            if ($participantesActuales >= $this->selectedEvento->numero_participantes) {
                throw new \Exception('No hay cupos disponibles para este evento.');
            }

            // Crear garantía individual
            $garantia = Garantia::create([
                'cliente_id' => $this->participante_cliente_id,
                'tipo_garantia_id' => 1, // Tipo por defecto
                'monto' => $this->participante_monto_garantia,
                'descripcion' => "Garantía para evento {$this->selectedEvento->numero_evento}",
                'estado' => Garantia::ESTADO_RECIBIDA,
                'fecha_recepcion' => now(),
                'sucursal_id' => $this->selectedEvento->sucursal_id,
                'usuario_recepcion_id' => Auth::id(),
            ]);

            // Generar número de participante
            $numeroParticipante = $this->generarNumeroParticipante($this->selectedEvento);

            // Crear participante
            $participante = EventoParticipante::create([
                'evento_id' => $this->selectedEvento->id,
                'cliente_id' => $this->participante_cliente_id,
                'garantia_id' => $garantia->id,
                'numero_participante' => $numeroParticipante,
                'nombre_completo' => $this->participante_nombre,
                'cedula' => $this->participante_cedula,
                'telefono' => $this->participante_telefono,
                'email' => $this->participante_email,
                'edad' => $this->participante_edad,
                'talla_general' => $this->participante_talla,
                'observaciones_especiales' => $this->participante_observaciones,
                'monto_garantia' => $this->participante_monto_garantia,
                'monto_participacion' => $this->participante_monto_participacion,
                'estado_participante' => EventoParticipante::ESTADO_REGISTRADO,
            ]);

            DB::commit();

            session()->flash('success', 'Participante registrado exitosamente.');
            $this->closeParticipanteModal();

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error al registrar participante: ' . $e->getMessage());
        }
    }

    // Métodos para asignación de vestimenta
    public function openVestimentaModal($participanteId)
    {
        $this->selectedParticipante = EventoParticipante::with(['evento', 'cliente', 'vestimentas.producto'])
                                                          ->find($participanteId);
        $this->vestimentaProductos = [];
        $this->showVestimentaModal = true;
    }

    public function closeVestimentaModal()
    {
        $this->showVestimentaModal = false;
        $this->selectedParticipante = null;
        $this->vestimentaProductos = [];
    }

    public function addProductoVestimenta()
    {
        if (!$this->currentProductId) return;

        $producto = Producto::find($this->currentProductId);
        $stock = StockPorSucursal::where('producto_id', $this->currentProductId)
                                ->where('sucursal_id', $this->selectedParticipante->evento->sucursal_id)
                                ->first();

        if (!$stock || $stock->stock_disponible <= 0) {
            session()->flash('error', 'No hay stock disponible para este producto.');
            return;
        }

        // Verificar si ya está agregado
        $exists = collect($this->vestimentaProductos)->contains('id', $this->currentProductId);
        if ($exists) {
            session()->flash('error', 'Este producto ya está agregado.');
            return;
        }

        $this->vestimentaProductos[] = [
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'tipo_vestimenta' => $producto->tipo_vestimenta,
            'stock_disponible' => $stock->stock_disponible,
        ];

        $this->currentProductId = '';
    }

    public function removeProductoVestimenta($index)
    {
        unset($this->vestimentaProductos[$index]);
        $this->vestimentaProductos = array_values($this->vestimentaProductos);
    }

    public function asignarVestimentas()
    {
        if (empty($this->vestimentaProductos)) {
            session()->flash('error', 'Debe seleccionar al menos una vestimenta.');
            return;
        }

        try {
            DB::beginTransaction();

            foreach ($this->vestimentaProductos as $producto) {
                // Verificar stock disponible nuevamente
                $stock = StockPorSucursal::where('producto_id', $producto['id'])
                                        ->where('sucursal_id', $this->selectedParticipante->evento->sucursal_id)
                                        ->lockForUpdate()
                                        ->first();

                if (!$stock || $stock->stock_disponible <= 0) {
                    throw new \Exception("No hay stock disponible para {$producto['nombre']}");
                }

                // Crear asignación de vestimenta
                EventoVestimenta::create([
                    'evento_id' => $this->selectedParticipante->evento_id,
                    'participante_id' => $this->selectedParticipante->id,
                    'producto_id' => $producto['id'],
                    'sucursal_id' => $this->selectedParticipante->evento->sucursal_id,
                    'cantidad' => 1,
                    'estado_vestimenta' => EventoVestimenta::ESTADO_ASIGNADA,
                ]);

                // Actualizar stock
                $stock->decrement('stock_disponible', 1);
                $stock->increment('stock_en_eventos', 1);

                // Registrar en historial
                HistorialProducto::registrarMovimiento([
                    'producto_id' => $producto['id'],
                    'tipo_movimiento' => HistorialProducto::TIPO_EVENTO,
                    'referencia_tipo' => HistorialProducto::REF_EVENTO,
                    'referencia_id' => $this->selectedParticipante->evento_id,
                    'sucursal_id' => $this->selectedParticipante->evento->sucursal_id,
                    'cantidad_anterior' => $stock->stock_disponible + 1,
                    'cantidad_movimiento' => 1,
                    'cantidad_posterior' => $stock->stock_disponible,
                    'usuario_id' => Auth::id(),
                    'observaciones' => "Asignado a participante {$this->selectedParticipante->numero_participante}",
                ]);
            }

            // Actualizar estado del participante
            $this->selectedParticipante->update([
                'estado_participante' => EventoParticipante::ESTADO_VESTIMENTA_ASIGNADA
            ]);

            DB::commit();

            session()->flash('success', 'Vestimentas asignadas exitosamente.');
            $this->closeVestimentaModal();

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error al asignar vestimentas: ' . $e->getMessage());
        }
    }

    // Métodos de finalización
    public function openFinalizarEventoModal($eventoId)
    {
        $this->selectedEvento = EventoFolklorico::with(['participantes.vestimentas.producto'])
                                                ->find($eventoId);
        $this->showFinalizarEventoModal = true;
    }

    public function closeFinalizarEventoModal()
    {
        $this->showFinalizarEventoModal = false;
        $this->selectedEvento = null;
    }

    public function finalizarEvento()
    {
        try {
            DB::beginTransaction();

            // Procesar devolución de todas las vestimentas
            foreach ($this->selectedEvento->participantes as $participante) {
                foreach ($participante->vestimentas as $vestimenta) {
                    if ($vestimenta->estado_vestimenta === EventoVestimenta::ESTADO_ASIGNADA ||
                        $vestimenta->estado_vestimenta === EventoVestimenta::ESTADO_ENTREGADA) {

                        $vestimenta->devolverVestimenta('Devolución automática al finalizar evento');
                    }
                }

                // Finalizar participante y liberar garantía
                $participante->finalizar();
            }

            // Actualizar estado del evento
            $this->selectedEvento->update([
                'estado' => EventoFolklorico::ESTADO_FINALIZADO,
                'total_real' => $this->selectedEvento->obtenerTotalRecaudado(),
            ]);

            DB::commit();

            session()->flash('success', 'Evento finalizado exitosamente. Todas las vestimentas han sido devueltas y las garantías liberadas.');
            $this->closeFinalizarEventoModal();

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error al finalizar evento: ' . $e->getMessage());
        }
    }

    // Métodos de utilidad
    private function generarNumeroEvento()
    {
        $year = Carbon::now()->year;
        $count = EventoFolklorico::whereYear('created_at', $year)->count() + 1;
        return 'EVT-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    private function generarNumeroParticipante($evento)
    {
        $count = $evento->participantes()->count() + 1;
        return $evento->numero_evento . '-P' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    private function resetEventoForm()
    {
        $this->nombre_evento = '';
        $this->descripcion = '';
        $this->tipo_evento = 'FESTIVAL';
        $this->institucion_organizadora = '';
        $this->fecha_evento = Carbon::now()->addDays(7)->format('Y-m-d');
        $this->hora_evento = '10:00';
        $this->lugar_evento = '';
        $this->direccion_evento = '';
        $this->numero_participantes = 0;
        $this->costo_por_participante = 0;
        $this->requiere_transporte = false;
        $this->observaciones = '';
    }

    private function resetParticipanteForm()
    {
        $this->participante_cliente_id = '';
        $this->participante_nombre = '';
        $this->participante_cedula = '';
        $this->participante_telefono = '';
        $this->participante_email = '';
        $this->participante_edad = '';
        $this->participante_talla = 'M';
        $this->participante_observaciones = '';
        $this->participante_monto_garantia = 0;
        $this->participante_monto_participacion = 0;
    }

    public function viewEvento($eventoId)
    {
        $this->selectedEvento = EventoFolklorico::with([
            'sucursal',
            'usuarioCreacion',
            'participantes.cliente',
            'participantes.garantia',
            'participantes.vestimentas.producto'
        ])->find($eventoId);
        $this->showViewEventoModal = true;
    }

    public function closeViewEventoModal()
    {
        $this->showViewEventoModal = false;
        $this->selectedEvento = null;
    }

    public function confirmarParticipante($participanteId)
    {
        $participante = EventoParticipante::find($participanteId);
        $participante->confirmarParticipacion();
        session()->flash('success', 'Participante confirmado exitosamente.');
    }

    public function cancelarParticipante($participanteId)
    {
        try {
            DB::beginTransaction();

            $participante = EventoParticipante::with(['vestimentas', 'garantia'])->find($participanteId);

            // Liberar vestimentas si están asignadas
            foreach ($participante->vestimentas as $vestimenta) {
                if ($vestimenta->estado_vestimenta === EventoVestimenta::ESTADO_ASIGNADA) {
                    $stock = StockPorSucursal::where('producto_id', $vestimenta->producto_id)
                                            ->where('sucursal_id', $vestimenta->sucursal_id)
                                            ->first();

                    if ($stock) {
                        $stock->increment('stock_disponible', 1);
                        $stock->decrement('stock_en_eventos', 1);
                    }
                }
                $vestimenta->delete();
            }

            // Liberar garantía
            if ($participante->garantia) {
                $participante->garantia->liberarGarantia();
            }

            // Cancelar participante
            $participante->update([
                'estado_participante' => EventoParticipante::ESTADO_CANCELADO
            ]);

            DB::commit();

            session()->flash('success', 'Participante cancelado y recursos liberados.');

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error al cancelar participante: ' . $e->getMessage());
        }
    }

    // Filtros
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedFilterEstado()
    {
        $this->resetPage();
    }

    public function updatedFilterTipo()
    {
        $this->resetPage();
    }

    public function updatedFilterSucursal()
    {
        $this->resetPage();
    }
}