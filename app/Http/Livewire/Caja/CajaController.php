<?php

namespace App\Http\Livewire\Caja;

use Livewire\Component;
use App\Models\Caja;
use App\Models\MovimientoCaja;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CajaController extends Component
{
    // Filtros y búsqueda
    public $busqueda = '';
    public $filtroEstado = '';
    public $filtroSucursal = '';
    
    // Propiedades para los modales
    public $mostrarModalCaja = false;
    public $mostrarModalMovimiento = false;
    public $mostrarModalCerrarCaja = false;
    public $mostrarModalMovimientos = false;

    // Form data para nueva caja
    public $nombre = '';
    public $descripcion = '';
    public $sucursal_id = '';
    public $es_caja_principal = false;
    public $saldo_inicial = 0;
    public $observaciones_apertura = '';

    // Form data para movimiento
    public $movimiento_caja_id = '';
    public $tipo = 'INGRESO';
    public $monto = 0;
    public $concepto = '';
    public $categoria = 'VARIOS';
    public $metodo_pago = 'EFECTIVO';
    public $referencia = '';
    public $observaciones = '';

    // Form data para cierre
    public $arqueo_fisico = 0;
    public $observaciones_cierre = '';

    // Cajas y movimientos seleccionados
    public $cajaSeleccionada = null;
    public $cajaParaCerrar = null;
    public $movimientosCaja = [];

    // Categorías disponibles
    public $categoriasDisponibles = [];

    public function mount()
    {
        $this->sucursal_id = Auth::user()->sucursal_id ?? '';
        $this->categoriasDisponibles = MovimientoCaja::obtenerCategoriasDisponibles();
    }

    public function render()
    {
        $cajas = $this->getFilteredCajas();
        $sucursales = Sucursal::orderBy('nombre')->get();
        $movimientosRecientes = $this->getMovimientosRecientes();

        return view('livewire.caja.caja', [
            'cajas' => $cajas,
            'sucursales' => $sucursales,
            'movimientosRecientes' => $movimientosRecientes,
        ]);
    }

    private function getFilteredCajas()
    {
        $query = Caja::with(['sucursal', 'usuarioApertura', 'usuarioCierre']);

        if ($this->busqueda) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', "%{$this->busqueda}%")
                    ->orWhereHas('sucursal', function ($sucursalQuery) {
                        $sucursalQuery->where('nombre', 'like', "%{$this->busqueda}%");
                    });
            });
        }

        if ($this->filtroEstado) {
            $query->where('estado', $this->filtroEstado);
        }

        if ($this->filtroSucursal) {
            $query->where('sucursal_id', $this->filtroSucursal);
        }

        return $query->orderBy('es_caja_principal', 'desc')
            ->orderBy('estado', 'desc')
            ->orderBy('nombre')
            ->get();
    }

    public function getCajasFiltradas()
    {
        return $this->getFilteredCajas();
    }

    private function getMovimientosRecientes()
    {
        return MovimientoCaja::with(['caja', 'usuarioRegistro'])
            ->orderBy('fecha_movimiento', 'desc')
            ->limit(10)
            ->get();
    }

    // Métodos para modales de caja
    public function abrirModalCaja($cajaId = null)
    {
        $this->resetFormulario();
        
        if ($cajaId) {
            $this->cajaSeleccionada = Caja::find($cajaId);
            if ($this->cajaSeleccionada) {
                $this->nombre = $this->cajaSeleccionada->nombre;
                $this->descripcion = $this->cajaSeleccionada->descripcion;
                $this->sucursal_id = $this->cajaSeleccionada->sucursal_id;
                $this->es_caja_principal = $this->cajaSeleccionada->es_caja_principal;
            }
        }
        
        $this->mostrarModalCaja = true;
    }

    public function cerrarModalCaja()
    {
        $this->mostrarModalCaja = false;
        $this->resetFormulario();
    }

    public function guardarCaja()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'sucursal_id' => 'required|exists:sucursals,id',
            'saldo_inicial' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            if ($this->cajaSeleccionada) {
                // Actualizar caja existente
                $this->cajaSeleccionada->update([
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                    'sucursal_id' => $this->sucursal_id,
                    'es_caja_principal' => $this->es_caja_principal,
                ]);
                $mensaje = 'Caja actualizada correctamente';
            } else {
                // Crear nueva caja
                $caja = Caja::create([
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                    'sucursal_id' => $this->sucursal_id,
                    'estado' => 'CERRADA',
                    'saldo_inicial' => 0,
                    'saldo_actual' => 0,
                    'es_caja_principal' => $this->es_caja_principal,
                ]);

                // Abrir la caja inmediatamente
                $caja->abrir($this->saldo_inicial, auth()->id(), $this->observaciones_apertura);
                $mensaje = 'Caja creada y abierta correctamente';
            }

            DB::commit();
            session()->flash('message', $mensaje);
            $this->cerrarModalCaja();

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error al guardar la caja: ' . $e->getMessage());
        }
    }

    // Métodos para movimientos
    public function abrirModalMovimiento()
    {
        $this->resetFormularioMovimiento();
        $this->mostrarModalMovimiento = true;
    }

    public function cerrarModalMovimiento()
    {
        $this->mostrarModalMovimiento = false;
        $this->resetFormularioMovimiento();
    }

    public function actualizarCategorias()
    {
        // Este método se puede usar para filtrar categorías por tipo si es necesario
        $this->categoria = 'VARIOS';
    }

    public function guardarMovimiento()
    {
        $this->validate([
            'movimiento_caja_id' => 'required|exists:cajas,id',
            'tipo' => 'required|in:INGRESO,EGRESO',
            'monto' => 'required|numeric|min:0.01',
            'concepto' => 'required|string|max:255',
            'categoria' => 'required|string',
        ]);

        try {
            $caja = Caja::find($this->movimiento_caja_id);
            
            if (!$caja || $caja->estado !== 'ABIERTA') {
                session()->flash('error', 'La caja seleccionada no está abierta');
                return;
            }

            $caja->registrarMovimiento(
                $this->tipo,
                $this->monto,
                $this->concepto,
                $this->categoria,
                auth()->id(),
                $this->referencia
            );

            session()->flash('message', 'Movimiento registrado correctamente');
            $this->cerrarModalMovimiento();

        } catch (\Exception $e) {
            session()->flash('error', 'Error al registrar movimiento: ' . $e->getMessage());
        }
    }

    // Métodos para cerrar caja
    public function abrirModalCerrarCaja($cajaId)
    {
        $this->cajaParaCerrar = Caja::find($cajaId);
        $this->arqueo_fisico = $this->cajaParaCerrar->saldo_actual;
        $this->observaciones_cierre = '';
        $this->mostrarModalCerrarCaja = true;
    }

    public function cerrarModalCerrarCaja()
    {
        $this->mostrarModalCerrarCaja = false;
        $this->cajaParaCerrar = null;
        $this->arqueo_fisico = 0;
        $this->observaciones_cierre = '';
    }

    public function cerrarCaja()
    {
        $this->validate([
            'arqueo_fisico' => 'required|numeric|min:0',
        ]);

        try {
            $this->cajaParaCerrar->cerrar($this->arqueo_fisico, auth()->id(), $this->observaciones_cierre);
            
            session()->flash('message', 'Caja cerrada correctamente');
            $this->cerrarModalCerrarCaja();

        } catch (\Exception $e) {
            session()->flash('error', 'Error al cerrar caja: ' . $e->getMessage());
        }
    }

    // Métodos para ver movimientos
    public function verMovimientos($cajaId)
    {
        $this->cajaSeleccionada = Caja::find($cajaId);
        $this->movimientosCaja = MovimientoCaja::where('caja_id', $cajaId)
            ->with(['usuarioRegistro', 'caja'])
            ->orderBy('fecha_movimiento', 'desc')
            ->get();
        $this->mostrarModalMovimientos = true;
    }

    public function cerrarModalMovimientos()
    {
        $this->mostrarModalMovimientos = false;
        $this->cajaSeleccionada = null;
        $this->movimientosCaja = [];
    }

    // Método para reabrir caja
    public function abrirCaja($cajaId)
    {
        $caja = Caja::find($cajaId);
        if ($caja && $caja->estado === 'CERRADA') {
            try {
                $caja->abrir(1000, auth()->id(), 'Reapertura de caja');
                session()->flash('message', 'Caja abierta correctamente');
            } catch (\Exception $e) {
                session()->flash('error', 'Error al abrir caja: ' . $e->getMessage());
            }
        }
    }

    // Métodos de utilidad
    private function resetFormulario()
    {
        $this->cajaSeleccionada = null;
        $this->nombre = '';
        $this->descripcion = '';
        $this->saldo_inicial = 0;
        $this->observaciones_apertura = '';
        $this->es_caja_principal = false;
    }

    private function resetFormularioMovimiento()
    {
        $this->movimiento_caja_id = '';
        $this->tipo = 'INGRESO';
        $this->monto = 0;
        $this->concepto = '';
        $this->categoria = 'VARIOS';
        $this->metodo_pago = 'EFECTIVO';
        $this->referencia = '';
        $this->observaciones = '';
    }

    // Listeners para actualización reactiva
    public function updatedBusqueda()
    {
        // Actualizar automáticamente cuando cambie la búsqueda
    }

    public function updatedFiltroEstado()
    {
        // Actualizar automáticamente cuando cambie el filtro
    }
}