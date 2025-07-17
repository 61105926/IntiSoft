<?php

use App\Http\Controllers\CajaController;
use App\Http\Controllers\CajaEntradaController;
use App\Http\Controllers\CajaSalidaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\StockSucursal;
use App\Http\Livewire\Caja\CajaLiveController;
use App\Http\Livewire\Cita\CitaController;
use App\Http\Livewire\Client\ClientController;
use App\Http\Livewire\Company\CompanyController;
use App\Http\Livewire\Contract\ContractController;
use App\Http\Livewire\Dashboard\DashboardController;
use App\Http\Livewire\Especie\EspecieController;
use App\Http\Livewire\Item\ItemController;
use App\Http\Livewire\Lactation\LactationController;
use App\Http\Livewire\Memoradum\MemoradumController;
use App\Http\Livewire\Person\PersonController;
use App\Http\Livewire\Pet\PetController;
use App\Http\Livewire\Pet\PetDetailController;
use App\Http\Livewire\Products\ProductsController;
use App\Http\Livewire\Proveedor\ProveedorController;
use App\Http\Livewire\ProveedorCompra\ProveedorCompraController;
use App\Http\Livewire\Raza\RazaController;
use App\Http\Livewire\RegisterFile\RegisterFileController;
use App\Http\Livewire\Roles\RolesController;
use App\Http\Livewire\Usuario\UsuarioController;
use App\Http\Livewire\Vacaciones\VacacionesController;
use App\Http\Livewire\Vacuna\VacunaController;
use App\Http\Livewire\Venta\VentaController;
use App\Models\HistorialMascota;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-permissions-seeder', function () {
    try {
        Artisan::call('db:seed', [
            '--class' => 'PermissionsSeeder',
        ]);
        return 'Permisos asignados correctamente al primer usuario.';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
})->middleware(['auth']);

Route::get('/run-migrations', function () {
    try {
        Artisan::call('migrate');
        return 'Migraciones ejecutadas correctamente.';
    } catch (\Exception $e) {
        return 'Error en las migraciones: ' . $e->getMessage();
    }
});
Route::get('/run-storage-link', function () {
    try {
        Artisan::call('storage:link');
        return 'El enlace simbólico de almacenamiento se creó correctamente.';
    } catch (\Exception $e) {
        return 'Error al crear el enlace simbólico de almacenamiento: ' . $e->getMessage();
    }
});

Route::get('/migrate-and-seed', function () {
    Artisan::call('migrate:fresh --seed');

    return 'Migraciones y seeding completados.';
});
Auth::routes();
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/usuario', UsuarioController::class)->middleware('can:user.view')->name('usuario');


    Route::get('/rool', RolesController::class)->middleware('can:user.view')->name('roles');

  

    Route::get('/ventas', VentaController::class)->middleware('can:sale.view')->name('ventas');



    Route::get('/generate-pdf/{id}', [VentaController::class, 'generatePdf'])->name('generate.pdf');
    Route::get('/ventas/reporte', [VentaController::class, 'generateSalesReportPdf'])->name('sales.report');

    Route::get('/create-symlink', function () {
        // Ejecutar el comando artisan
        Artisan::call('storage:link');
        return 'Enlace simbólico creado con éxito.';
    });
 
    // Route::get('caja/{id}', [CajaController::class, 'edit'])->name('caja.edit');

    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion');
    Route::get('/cliente', [ClienteController::class, 'index'])->name('cliente');
    Route::get('/producto', [ProductoController::class, 'index'])->name('producto');
    Route::get('/sucursal', [StockSucursal::class, 'index'])->name('sucursal');


});
