<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Services;
use App\Models\Proveedores;
use App\Models\Category;
use Illuminate\Support\Facades\Crypt;

class MarketController extends Controller
{
      /**
       * Create a new controller instance with authenticate.
       *
       * @return void
       */
      public function __construct()
      {
          //Solicitamos que el usuario este registrado para continuar
          $this->middleware('auth');
      }

      /**
       * Show the application product list.
       *
       * @return \Illuminate\Http\Response
       */
      public function index()
      {
          // Obtenemos las categorías del modelo Category
          $categorys = Category::where('state', 1)->get();

          // Todos los productos con estado Activos (1) y paginados de 20 en 20
          $productos = Product::with(['gallery', 'category', 'proveedor'])->where('state', 1)->paginate(20);

          //Se llama a la vista "lista" ruta resources/views/market/lista.
          return view('market.lista')->with('productos', $productos)->with('categorys', $categorys);
      }

      /**
       * Show the application product list.
       *
       * @return \Illuminate\Http\Response
       */
      public function licitaciones()
      {

          // Todos los productos con estado Activos (1) y paginado de 20 en 20
          $licitaciones = Services::with(['gallery', 'category', 'proveedor'])->where('state', 1)->orderBy('created_at', 'DESC')->paginate(20);

          // Se llama a la vista "lista" ruta /resources/views/market/lista */
          return view('market.licitaciones')->with('licitaciones', $licitaciones);
      }

      /**
       * Show the application market product list with category.
       *
       * @return \Illuminate\Http\Response
       */
      public function category($category=null)
      {
          // Desencriptamos el id de categoría.
          $id = Crypt::decrypt($category);

          // Obtenemos las categorías.
          $categorys = Category::where('state', 1)->get();

          // Todos los productos con estado Activos (1) con paginate y order by desc.
          $productos = Product::with(['gallery', 'proveedor', 'category'])->where('category_id', $id)->where('state', 1)->orderBy('created_at', 'DESC')->paginate(20);

          // Se llama a la vista lista ruta ./resources/views/market/lista.
          return view('market.lista')->with('productos', $productos)->with('categorys', $categorys);
      }

      /**
       * Show the application product list search result.
       *
       * @return \Illuminate\Http\Response
       */
      public function search($buscar=null)
      {
          // Obtenemos las categorías.
          $categorys = Category::where('state', 1)->get();

          // Todos los productos con estado Activos (1) y utilizamos paginate para paginar resultados de 20 en 20.
          $productos = Product::where('name','like', '%' . $buscar . '%')->paginate(20);

          // Se llama a la vista lista ruta ./resources/views/market/lista.
          return view('market.lista')->with('productos', $productos)->with('categorys', $categorys);
      }

      /**
       * Show the application product selected.
       *
       * @return \Illuminate\Http\Response
       */
      public function view($id=null)
      {
          // Desencriptamos del id de producto.
          $id = Crypt::decrypt($id);

          // Todos los datos del productos seleccionado.
          $producto = Product::with(['gallery', 'proveedor', 'category'])->where('id', $id)->where('state', 1)->first();

          // Se llama a la vista "view" ruta ./resources/views/market/view.
          return view('market.view')->with('producto', $producto);
      }
}