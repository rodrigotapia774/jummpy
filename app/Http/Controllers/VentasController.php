<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Services;
use App\Models\Proveedores;

class VentasController extends Controller
{
    /**
     * Create a new controller instance with authenticate.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application services list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //Todos los productos con estado Activos (1).
      $ventas = Product::where('state', 1)->get();

      //Se llama a la vista lista que se encuentra en la siguiente ruta ./resources/views/ventas/productos/lista.
      return view('ventas.lista')->with('ventas', $ventas);
    }
}