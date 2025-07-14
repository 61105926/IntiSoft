<?php

use App\Http\Controllers\CajaController;
use App\Http\Controllers\CajaEntradaController;
use App\Http\Controllers\CajaSalidaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracionController;
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
    Route::get('/dashboard', DashboardController::class)->middleware('can:dashboard.view')->name('dashboard');

    Route::get('/usuario', UsuarioController::class)->middleware('can:user.view')->name('usuario');
    Route::get('/raza', RazaController::class)->middleware('can:breed.view')->name('razas');
    Route::get('/especie', EspecieController::class)->middleware('can:species.view')->name('especie');
    Route::get('/vacuna', VacunaController::class)->middleware('can:vaccine.view')->name('vacuna');
    Route::middleware(['can:pet.view'])->group(function () {
        Route::get('/mascotadetalle/{id}', PetDetailController::class)->middleware('can:pet.detail')->name('pet.detail');
    });

    Route::get('/personal', PersonController::class);
    Route::get('/rool', RolesController::class)->middleware('can:user.view')->name('roles');

    Route::get('/company', CompanyController::class)->middleware('can:user.view')->name('company');
    Route::get('/client', ClientController::class)->middleware('can:client.view')->name('client');
    Route::middleware(['can:pet.view'])->group(function () {
        Route::get('/pet', PetController::class)->name('pet');
    });

    Route::get('/inventory', ProductsController::class)->middleware('can:inventory.view')->name('inventory');
    Route::get('/ventas', VentaController::class)->middleware('can:sale.view')->name('ventas');

    Route::middleware(['can:provider.view'])->group(function () {
        Route::get('/proveedor', ProveedorController::class)->name('proveedor');
        Route::get('/proveedor-compra', ProveedorCompraController::class)->middleware('can:purchase.view')->name('proveedorcompra');
    });

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

});
