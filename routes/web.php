<?php

use Illuminate\Support\Facades\Route;
/**
*Llamamos a los controladores que ocuparemos en la App
*/
use App\Http\Controllers\VentasController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ServiciosController;
use App\Http\Controllers\MarketController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/**
 * Rutas para el componente de market place
 */
Route::get('/market', [MarketController::class, 'index']);
Route::get('/market/category/{id}', [MarketController::class, 'category']);
Route::get('/market/search/{id}', [MarketController::class, 'search']);
Route::get('/market/view/{id}', [MarketController::class, 'view']);

/**
 * Rutas para el componente de market licitaciones
 */
Route::get('/market-services', [MarketController::class, 'licitaciones']);
Route::get('/market-services/search/{id}', [MarketController::class, 'licitacionesSearch']);
Route::get('/market-servicest/view/{id}', [MarketController::class, 'licitacion']);

/**
 * Rutas para el componente de ventas
 */
Route::get('/ventas', [VentasController::class, 'index']);

/**
 * Rutas para el componente de servicios
 */
Route::get('/servicios', [ServiciosController::class, 'index'])->name('servicios');
Route::get('/servicios-inactive', [ServiciosController::class, 'inactive'])->name('servicios-inactive');
Route::get('/servicios/create', [ServiciosController::class, 'create']);
Route::get('/servicios/search/{id}', [ServiciosController::class, 'search']);
Route::post('/servicios/create', [ServiciosController::class, 'insert'])->name('servicios/create');
Route::get('/servicios/update/{id}', [ServiciosController::class, 'edit']);
Route::post('/servicios/update', [ServiciosController::class, 'update'])->name('servicios/update');
Route::get('/servicios/inactive/{id}', [ServiciosController::class, 'desactive']);
Route::get('/servicios/active/{id}', [ServiciosController::class, 'active']);
Route::get('/servicios/delete/{id}', [ServiciosController::class, 'delete']);

/**
 * Rutas para el componente de productos
 */
Route::get('/productos', [ProductosController::class, 'index'])->name('productos');
Route::get('/productos-inactive', [ProductosController::class, 'inactive'])->name('productos-inactive');
Route::get('/productos/create', [ProductosController::class, 'create']);
Route::get('/productos/search/{id}', [ProductosController::class, 'search']);
Route::post('/productos/create', [ProductosController::class, 'insert'])->name('productos/create');
Route::get('/productos/update/{id}', [ProductosController::class, 'edit']);
Route::post('/productos/update', [ProductosController::class, 'update'])->name('productos/update');
Route::get('/productos/inactive/{id}', [ProductosController::class, 'desactive']);
Route::get('/productos/active/{id}', [ProductosController::class, 'active']);
Route::get('/productos/delete/{id}', [ProductosController::class, 'delete']);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
