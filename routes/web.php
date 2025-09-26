<?php

use App\Http\Controllers\AlquileresController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\Dashboard;
use App\Http\Livewire\EntradaFolclorica\EntradaFolcloricaController;
use App\Http\Controllers\EntradaFolcloricaViewController;
use App\Http\Livewire\EventoFolklorico\EventoFolkloricoController;
use App\Http\Livewire\Stock\StockController;
use App\Http\Controllers\GarantiaController;
use App\Http\Controllers\HistorialProductoController;
use App\Http\Livewire\HistorialProducto\HistorialProductoController as LivewireHistorialProductoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\StockSucursal;
use App\Http\Controllers\VentaController as ControllersVentaController;
use App\Http\Livewire\Roles\RolesController;
use App\Http\Livewire\Usuario\UsuarioController;

use App\Http\Livewire\Venta\VentaController;

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


    Route::get('/rool', RolesController::class)->middleware('can:user.view')->name('rool');

  




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
    Route::get('/reserva', [ReservaController::class, 'index'])->name('reserva');

    Route::get('/alquiler', [AlquileresController::class, 'index'])->name('alquiler');
    Route::get('/garantias', [GarantiaController::class, 'index'])->name('garantia');
    Route::get('/historial-producto', LivewireHistorialProductoController::class)->name('historial-producto');
    Route::get('/venta', [ControllersVentaController::class, 'index'])->name('venta');
    Route::get('/caja', [CajaController::class, 'index'])->name('caja');
    Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');
    Route::get('/entrada-folklorica', [EntradaFolcloricaViewController::class, 'index'])->name('entrada-folklorica');
    Route::get('/entrada-folklorica/{id}/participantes', [EntradaFolcloricaViewController::class, 'participantes'])->name('entrada-folklorica.participantes');
    Route::get('/entrada-folklorica/{id}/devoluciones', [EntradaFolcloricaViewController::class, 'devoluciones'])->name('entrada-folklorica.devoluciones');

    // Nuevos módulos ERP
    Route::get('/eventos-folkloricos', EventoFolkloricoController::class)->name('eventos-folkloricos');
    Route::get('/stock-sucursal', StockController::class)->name('stock-sucursal');


});
