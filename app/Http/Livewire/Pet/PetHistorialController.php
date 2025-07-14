<?php

namespace App\Http\Livewire\Pet;

use App\Models\HistorialArchivos;
use App\Models\HistorialMascota;
use App\Models\HistorialVacuna;
use App\Models\Peluqueria;
use App\Models\Vacuna;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetHistorialController extends Component
{
    use WithFileUploads;

    public $motivoConsulta;
    public $sintomas;
    public $diagnostico;
    public $tratamiento;
    public $documentos = [];
    public $titulosDocumentos = []; // Aquí almacenaremos los títulos de los documentos.
    public $mascotaId;
    public $activeTab = 'historial'; // Pestaña activa por defecto
    public $documentosEnCarga = [];

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }
    public function mount($petId)
    {
        $this->mascotaId = $petId;
        $this->historiales = HistorialMascota::where('pets_id', $this->mascotaId)->get();
        $this->fechaVacuna = now(); // Inicializa con la fecha actual

        // Iniciar con un campo para un archivo vacío y su respectivo título
        // $this->addDocumento();
    }
    public function addDocumento()
    {
        $this->documentos[] = null;
        $this->titulosDocumentos[] = '';
        $this->documentosEnCarga[] = false;
    }

    public function removeDocumento($index)
    {
        unset($this->documentos[$index]);
        unset($this->titulosDocumentos[$index]);
        $this->documentos = array_values($this->documentos); // Reindexar array.
        $this->titulosDocumentos = array_values($this->titulosDocumentos); // Reindexar array.
    }

    public function guardarHistorial()
    {
        try {
            $this->validate([
                'motivoConsulta' => 'required',
                'sintomas' => 'required',
                'diagnostico' => 'required',
                'tratamiento' => 'required',
                'documentos.*.archivo' => 'file', // Ajusta según tus necesidades
                'documentos.*.titulo' => 'required',
            ]);

            // Crear el historial de la mascota
            $historial = HistorialMascota::create([
                'pets_id' => $this->mascotaId, // Asegúrate de tener el ID de la mascota
                'motivo_consulta' => $this->motivoConsulta,
                'sintomas' => $this->sintomas,
                'diagnostico' => $this->diagnostico,
                'tratamiento' => $this->tratamiento,
            ]);

            // Manejo de archivos
            if ($this->documentos) {
                foreach ($this->documentos as $index => $documento) {
                    if ($documento instanceof \Livewire\TemporaryUploadedFile) {
                        // Guardar el archivo y obtener la ruta
                        $rutaArchivo = $documento->store('documentos', 'public');

                        // Crear el registro del archivo con el título correspondiente
                        HistorialArchivos::create([
                            'historial_id' => $historial->id,
                            'titulo' => $this->titulosDocumentos[$index] ?? 'Sin título',
                            'archivo' => $rutaArchivo,
                        ]);
                    }
                }
            }

            // Reiniciar el formulario
            // $this->reset();
            session()->flash('messageHistorial', 'Nota guardada exitosamente.');

            return redirect()->route('pet.detail', ['id' => $this->mascotaId]);
        } catch (\Throwable $th) {
            //throw $th;
            // dd($th);
            $this->emit('show-alert', ['title' => 'Falta El Documento o Titulo', 'type' => 'error']);


        }
        // Validar los datos antes de guardar
    }
    public $historiales = []; // Suponiendo que este array tiene los datos de historial
    public $expandedHistorials = []; // Para controlar qué historiales están expandidos

    public function toggleHistorial($index)
    {
        if (in_array($index, $this->expandedHistorials)) {
            $this->expandedHistorials = array_diff($this->expandedHistorials, [$index]); // Remover del array
        } else {
            $this->expandedHistorials[] = $index; // Agregar al array
        }
    }
    public $vacunaId; // ID de la vacuna seleccionada

    public $notaVacuna; // Notas adicionales para la vacuna
    public $producto; // Notas adicionales para la vacuna
    public $veterinaria; // Notas adicionales para la vacuna
    public $referencia; // Notas adicionales para la vacuna
    public $observaciones; // Notas adicionales para la vacuna
    public $fechaVacuna;

    

    public function guardarVacuna()
    {
        // Validar los datos de la vacuna
        $this->validate([
            'producto' => 'required',
            'veterinaria' => 'required',
            'referencia' => 'required',
            'observaciones' => 'required',
        ]);

        // Crear un nuevo registro de vacunación
        HistorialVacuna::create([
            'pets_id' => $this->mascotaId,
            'vacuna_id' => $this->vacunaId,
            'producto' => $this->producto,
            'veterinaria' => $this->veterinaria,
            'referencia' => $this->referencia,
            'observaciones' => $this->observaciones,
            'created_at' => $this->fechaVacuna,
        ]);

        session()->flash('messageVacuna', 'Vacuna registrada exitosamente.');

        // Limpiar los campos de entrada
        $this->vacunaId = null;
        $this->notaVacuna = null;
        $this->producto = null;
        $this->veterinaria = null;
        $this->referencia = null;
        $this->observaciones = null;

        // Recargar la página
        $this->emit('closeVacunaModal');

        // Recargar la página
    }
    public function editVacuna($id)
    {
        $this->id_vacuna = $id;

        $vacuna = HistorialVacuna::findOrFail($id);
        $this->vacunaId = $vacuna->vacuna_id;
        $this->fechaVacuna = $vacuna->created_at->format('Y-m-d'); // Formato adecuado para campo de fecha HTML
        $this->producto = $vacuna->producto;
        $this->veterinaria = $vacuna->veterinaria;
        $this->referencia = $vacuna->referencia;
        $this->observaciones = $vacuna->observaciones;
    }

    public function updateVacuna()
    {
        $vacuna = HistorialVacuna::findOrFail($this->id_vacuna);
        $vacuna->created_at = $this->fechaVacuna;
        $vacuna->vacuna_id = $this->vacunaId;

        $vacuna->producto = $this->producto;
        $vacuna->veterinaria = $this->veterinaria;
        $vacuna->referencia = $this->referencia;
        $vacuna->observaciones = $this->observaciones;
        $vacuna->save();
        $this->emit('updateVacunaModal');

        session()->flash('message', 'Vacuna actualizada correctamente.');
    }
    public function eliminarHistorial($historialId)
    {
        try {
            // Busca el historial y los documentos asociados
            $historial = HistorialMascota::with('documentos')->find($historialId);

            if ($historial) {
                // Usa una transacción para asegurar la consistencia de los datos
                DB::transaction(function () use ($historial) {
                    // Elimina los archivos en la base de datos
                    foreach ($historial->documentos as $documento) {
                        Storage::disk('public')->delete($documento->archivo);
                    }

                    // Elimina el historial
                    $historial->delete();

                    // Elimina los archivos temporales de Livewire
                    foreach ($historial->documentos as $documento) {
                        if ($documento instanceof \Livewire\TemporaryUploadedFile) {
                            $documento->delete();
                        }
                    }
                });

                // Emitir evento para mostrar notificación
                $this->emit('show-alert', [
                    'title' => 'Historial eliminado exitosamente',
                    'type' => 'success'
                ]);
            } else {
                $this->emit('show-alert', [
                    'title' => 'Historial no encontrado',
                    'type' => 'error'
                ]);
            }

            // Recargar los historiales
            $this->mount($this->mascotaId);

        } catch (\Exception $e) {
            $this->emit('show-alert', [
                'title' => 'Error al eliminar el historial',
                'type' => 'error'
            ]);
        }
    }

    public $id_vacuna;

 

    public function deleteVacuna($id)
    {
        $vacuna = HistorialVacuna::findOrFail($id);
        $vacuna->delete();
        $this->setActiveTab('vacunas');

        session()->flash('messageVacuna', 'Vacuna eliminada exitosamente.');
    }
    public $pets_id;
    public $imagen;
    public $numero_cuchilla;
    public $tipo_corte;
    public $precio;
    public $idPeluqueria;
    public $fecha_corte;

    protected $rules = [
        'pets_id' => 'required|exists:pets,id',
        'imagen' => 'nullable|image|max:1024', // Solo imágenes, máximo 1MB
        'numero_cuchilla' => 'required|integer',
        'tipo_corte' => 'required|string',
        'fecha_corte' => 'required',
        'precio' => 'required|numeric|min:0',
    ];

    public function savePeluqueria()
    {
        $this->validate([
            'numero_cuchilla' => 'required|integer',
            'tipo_corte' => 'required|string',
            'precio' => 'required|numeric',
            'imagen' => 'nullable|image|max:2048',
        ]);

        // Almacenar la imagen si existe
        $imagenPath = $this->imagen ? $this->imagen->store('peluquerias', 'public') : null;

        Peluqueria::create([
            'pets_id' => $this->mascotaId,
            'imagen' => $imagenPath,
            'numero_cuchilla' => $this->numero_cuchilla,
            'tipo_corte' => $this->tipo_corte,
            'precio' => $this->precio,
            'created_at' => $this->fecha_corte,
        ]);

        $this->emit('closePeluqueriaModal');
        session()->flash('messagePeluqueria', 'Servicio de peluquería guardado exitosamente.');

        // Resetear los campos
        $this->reset(['imagen', 'numero_cuchilla', 'tipo_corte', 'precio']);
    }

    public function edit($id)
    {
        $peluqueria = Peluqueria::findOrFail($id);

        $this->idPeluqueria = $peluqueria->id; // Guardar el ID
        $this->imagen = null; // Reiniciar imagen para prevenir problemas
        $this->numero_cuchilla = $peluqueria->numero_cuchilla;
        $this->tipo_corte = $peluqueria->tipo_corte;
        $this->precio = $peluqueria->precio;
        $this->fecha_corte = $peluqueria->created_at->format('Y-m-d'); // Formatear la fecha para el campo date
    }

    public function updatePeluqueria()
    {
        $this->validate([
            'numero_cuchilla' => 'required|integer',
            'tipo_corte' => 'required|string',
            'precio' => 'required|numeric',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $peluqueria = Peluqueria::find($this->idPeluqueria);

        if (!$peluqueria) {
            session()->flash('error', 'Peluquería no encontrada.');
            return;
        }

        // Si hay una nueva imagen, la procesamos
        if ($this->imagen) {
            // Eliminar la imagen anterior si existe
            if ($peluqueria->imagen) {
                Storage::disk('public')->delete($peluqueria->imagen);
            }

            // Guardar la nueva imagen
            $imagenPath = $this->imagen->store('peluquerias', 'public');
            $peluqueria->imagen = $imagenPath;
        }

        // Actualizar los demás datos
        $peluqueria->numero_cuchilla = $this->numero_cuchilla;
        $peluqueria->tipo_corte = $this->tipo_corte;
        $peluqueria->precio = $this->precio;
        $peluqueria->created_at = $this->fecha_corte;


        $peluqueria->save();

        session()->flash('messagePeluqueria', 'Servicio de peluquería actualizado correctamente.');
        $this->emit('updatePeluqueriaModal');

        // Resetear los campos
        $this->reset(['imagen', 'numero_cuchilla', 'tipo_corte', 'precio']);
    }
public $deleteId;
    // Eliminar peluquería
    public function setDeleteId($id)
    {
        $this->deleteId = $id;
    }

    public function deletePeluqueria()
    {
        $peluqueria = Peluqueria::find($this->deleteId);
        $peluqueria->delete();
        session()->flash('messagePeluqueria', 'Servicio de peluquería eliminado correctamente.');
        $this->emit('deletePeluqueriaModal');
    }
    public function render()
    {
        $historialVacunas = HistorialVacuna::where('pets_id', $this->mascotaId)

            ->orderBy('created_at', 'desc')
            ->get();

        $historialPeluqueria = Peluqueria::where('pets_id', $this->mascotaId)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('livewire.pet.pet-historial', [
            'vacunas' => Vacuna::all(), // Obtener todas las vacunas
            'historialVacunas' => $historialVacunas, // Pasar historial de vacunas a la vista
            'historialPeluqueria' => $historialPeluqueria, // Pasar historial de peluquería a la vista
        ]);
    }
public $historial_id;
    public function editHistorial($id)
{
    $historial = HistorialMascota::findOrFail($id);
    
    $this->historial_id = $historial->id;
    $this->motivoConsulta = $historial->motivo_consulta;
    $this->sintomas = $historial->sintomas;
    $this->diagnostico = $historial->diagnostico;
    $this->tratamiento = $historial->tratamiento;
    
    
    
    // Agregar un documento vacío para permitir nuevas adiciones
    // $this->addDocumento();
}
    public function updateHistorial()
    {
        $this->validate([
            'motivoConsulta' => 'required',
            'sintomas' => 'required',
            'diagnostico' => 'required',
            'tratamiento' => 'required',
        ]);

        try {
            DB::beginTransaction();
            
            $historial = HistorialMascota::find($this->historial_id);
            $historial->update([
                'motivo_consulta' => $this->motivoConsulta,
                'sintomas' => $this->sintomas,
                'diagnostico' => $this->diagnostico,
                'tratamiento' => $this->tratamiento,
            ]);

            // Manejo de nuevos documentos
            if ($this->documentos) {
                foreach ($this->documentos as $index => $documento) {
                    if ($documento instanceof \Livewire\TemporaryUploadedFile) {
                        $rutaArchivo = $documento->store('documentos', 'public');
                        HistorialArchivos::create([
                            'historial_id' => $historial->id,
                            'titulo' => $this->titulosDocumentos[$index] ?? 'Sin título',
                            'archivo' => $rutaArchivo,
                        ]);
                    }
                }
            }

            DB::commit();
            session()->flash('messageHistorial', 'Historial actualizado correctamente.');
            
            // Emitir ambos eventos para asegurar que el modal se cierre
            $this->emit('closeEditHistorialModal');
            $this->emit('historialActualizado');
            
            $this->resetUI();
            $this->mount($this->mascotaId);

        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error al actualizar el historial: ' . $e->getMessage());
        }
    }

    // Asegúrate de tener este método resetUI
    public function resetUI()
    {
        $this->reset([
            'motivoConsulta', 
            'sintomas', 
            'diagnostico', 
            'tratamiento',
            'documentos',
            'titulosDocumentos',
            'historial_id'
        ]);
    }

    public function eliminarDocumento($documentoId)
    {
        try {
            $documento = HistorialArchivos::find($documentoId);
            
            if ($documento) {
                // Eliminar el archivo físico
                if (Storage::disk('public')->exists($documento->archivo)) {
                    Storage::disk('public')->delete($documento->archivo);
                }
                
                // Eliminar el registro de la base de datos
                $documento->delete();
                
                session()->flash('messageHistorial', 'Documento eliminado correctamente.');
            }
        $this->mount($this->mascotaId); // Método para cargar los historiales de nuevo

        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el documento: ' . $e->getMessage());
        }
    }

    public function updatingDocumentos($value, $key)
    {
        $index = explode('.', $key)[0];
        $this->documentosEnCarga[$index] = true;
    }

    public function updatedDocumentos($value, $key)
    {
        // Emitir evento cuando se complete la carga
        $this->emit('documentoSubido');
    }
}
