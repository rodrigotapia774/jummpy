<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Services;
use App\Models\Proveedores;
use App\Models\Category;
use App\Models\Gallery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Providers\RouteServiceProvider;
use DateTime;
use File;

class ProductosController extends Controller
{
    /**
     * Create a new controller instance with authenticate.
     *
     * @return void
     */
    public function __construct()
    {
        //Solicitamos que el usuario este registrado para continuar.
        $this->middleware('auth');
    }

    /**
     * Show the application product list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // Todos los productos con estado Activos (1) y utilizamos pagiate para paginar resultados de 10 en 10 con order descendente.
      $productos = Product::where('state', 1)->orderBy('created_at', 'DESC')->paginate(10);

      // Se llama a la vista "lista" ruta ./resources/views/productos/lista.
      return view('productos.lista')->with('productos', $productos);
    }

    /**
     * Show the application product list inactive.
     *
     * @return \Illuminate\Http\Response
     */
    public function inactive()
    {
      // Todos los productos con estado Activos (1) y utilizamos pagiate para paginar resultados de 20 en 20 con orden descendente.
      $productos = Product::where('state', 2)->orderBy('created_at', 'DESC')->paginate(20);

      // Se llama a la vista "lista" ruta ./resources/views/productos/lista.
      return view('productos.lista')->with('productos', $productos);
    }

    /**
     * Show the application product list search result.
     *
     * @return \Illuminate\Http\Response
     */
    public function search($buscar = null)
    {
        // Todos los productos con estado Activos (1) y utilizamos pagiate para paginar resultados de 12 en 120 con orden descendente.
        $productos = Product::where('name','like', '%' . $buscar . '%')->orderBy('created_at', 'DESC')->paginate(20);

        // Se llama a la vista "lista" ruta ./resources/views/productos/lista.
        return view('productos.lista')->with('productos', $productos);
    }

    /**
     * Mostramos el formulario para el nuevo registro de producto.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Todos las categorías con estado Activos (1)./
        $categorys = Category::where('state', 1)->get();

        // Se llama a la vista "lista" ruta ./resources/views/productos/create.
        return view('productos.create')->with('categorys', $categorys);
    }

    /**
     * Validamos e insertamos los datos entregados por el formulario.
     *
     * @param  Request  $request
     * @return Response
     */
    public function insert(Request $request)
    {
        //Validamos los datos
        $this->validate($request, [
            'name' => 'required|max:250',
            'category' => 'required',
            'cantidad' => 'required|numeric',
            'valor' => 'required|numeric',
            'oferta' => 'nullable|numeric',
            'resumen' => 'required|max:250',
            'descripcion' => 'required',
            'galery.*' => 'image'
        ]);

        //Obteemos el Id de la compañia del usuario.
        $id_company = Auth::user()->company_id;

        // Obetenemos el Id del usuario.
        $id_user = Auth::user()->id;

        // Registramos el producto y obtenemos los datos del nuevo registro.
        $product = Product::create([
            'user_id' => $id_user,
            'proveedor_id' => $id_company,
            'category_id' => $request->category,
            'name' => $request->name,
            'resume' => $request->resumen,
            'content' => $request->descripcion,
            'price' => $request->valor,
            'sale' => $request->oferta,
            'total' => $request->cantidad,
            'state' => 1
        ]);

        // Id del nuevo registro del producto.
        $id = $product->id;

        //Verificamos que exista el campo galery y que no se encuetre vacio.
        if(isset($request->galery) && !empty($request->galery)): 
            //Recorremos los resultados de gallery con un foreach.
            foreach($request->galery as $fileInput):

                //Enviamos la imagen a la función uploadfile para ser almacenada en servidor y BD.
                $this->uploadfile($fileInput, $id, $id_user);

            endforeach;

        endif;

        //Volvemos al formulario de registro, con un mensje de éxito.
        return back()->with('success','Producto agregado con éxito!');
    }

    /**
     * Mostramos un formulario con los datos del producto seleccionado.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      //Desencriptamos el ID
      $id = Crypt::decrypt($id);

      //Obtenemos el Id de la compañia a la que pertenece el usuario.
      $id_company = Auth::user()->company_id;

      //Realizamos la consulta pero realizamos dos validaciones, la primera es que busque con elid del producto y la segunda que el producto pertenezca a la comañia del cliente.
      $datos = Product::with(['gallery', 'category'])->where('id', $id)->where('proveedor_id', $id_company)->first();

      // Todos las categorias con estado Activos (1).
      $categorys = Category::where('state', 1)->get();

      //Se llama a la vista "lista" ruta ./resources/views/ventas/productos/create.
      return view('productos.update')->with('categorys', $categorys)->with('datos', $datos);
    }

    /**
     * Validamos e insertamos los datos entregados por el formulario.
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'id_oculto' => 'required',
            'name' => 'required|max:250',
            'category' => 'required',
            'cantidad' => 'required|numeric',
            'valor' => 'required|numeric',
            'oferta' => 'nullable|numeric',
            'resumen' => 'required|max:250',
            'descripcion' => 'required',
            'estado' => 'required',
            'galery.*' => 'image'
        ]);

        //Desencriptamos el id_oculto del producto.
        $id = Crypt::decrypt($request->id_oculto);

        //Obtenemos el Id de lacompañia del usuario.
        $id_company = Auth::user()->company_id;

        //Obtenemos elId del usuario.
        $id_user = Auth::user()->id;

        //Realizamos el UPDATE a la base de datos, tambien realzamos doble where para mayor validación, de esta forma solo el usuario que pertenece a la misma compañía que el producto podra modificar los datos.
        Product::where('id', $id)->where('proveedor_id', $id_company)->update(
            [
                'category_id' => $request->category,
                'name' => $request->name,
                'resume' => $request->resumen,
                'content' => $request->descripcion,
                'price' => $request->valor,
                'sale' => $request->oferta,
                'total' => $request->cantidad,
                'state' => $request->estado
            ]
        );

       // Obtenemos las imagenes del producto para recorrer, comparar y eliminar si no se encuetra similitud con los registros de la galería del formulario.
       $imagenes = Gallery::where('product_id', $id)->get();

       foreach($imagenes as $img){
            $aux = 0;
            foreach($request->images as $images){
                if($img->id == $images){
                    $aux = 1;
                }
            }

            if($aux == 0) {
                $filename = public_path().'/uploads/'.$img->image;
                File::delete($filename);
                Gallery::where('id', $img->id)->delete();
            }
        }

        // Recorremos las nuevas imagenes agregadas al formulario para ser alamacenadas en la BD y elservidor.
        if(isset($request->galery) && !empty($request->galery)){
            foreach($request->galery as $fileInput){

                //Enviamos la imagen a la función uploadfile con el id del producto y del usuario.
                $this->uploadfile($fileInput, $id, $id_user);

            }

        }

        //Redireccionamos al formulario con un mensaje de éxito.
        return back()->with('success','Producto modificado con éxito!');
    }

    /**
     * Desactivamos el producto dejandolo en estado 2.
     *
     * @return \Illuminate\Http\Response
     */
    public function desactive($id)
    { 
        //Desencriptamos el id del producto
        $id = Crypt::decrypt($id);

        //Obetenemos el id de la compañia del usuario.
        $id_company = Auth::user()->company_id;

        //Se realizan 2 verificaciones para desactivar el producto. 1) el Id del producto y 2) el proveedor del producto sea igual al id de la compañía del usuario.
        Product::where('id', $id)->where('proveedor_id', $id_company)->update(
            [ 
                'state' => 2
            ]
        );

        return back()->with('success','Producto desactivado con éxito!');
    }

    /**
     * Activamos el producto dejandolo en estado 1.
     *
     * @return \Illuminate\Http\Response
     */
    public function active($id)
    {
        $id = Crypt::decrypt($id);

        $id_company = Auth::user()->company_id;

        //Se realizan 2 verificaciones para activar el producto. 1) el Id del producto y 2) el proveedor del producto debe ser igual al id de la compañia del usuario.
        Product::where('id', $id)->where('proveedor_id', $id_company)->update(
            [ 
                'state' => 1
            ]
        );

        return back()->with('success','Producto activado con éxito!');
    }

    /**
     * Eliminamos el producto y sus imagenes.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $id = Crypt::decrypt($id);

        $id_company = Auth::user()->company_id;

        //Se realizan 2 verificaciones para eliminar el producto. 1) el Id del producto y 2) el proveedor del producto debe ser igual al id de la compañ+ia del usuario.
        Product::where('id', $id)->where('proveedor_id', $id_company)->delete();

        //Se obtienen las imagenes del producto.
        $imagenes = Gallery::where('product_id', $id)->get();

        //Se recorren las imagenes y se eliminan del servidor y la base de datos.
        foreach($imagenes as $img){
            $filename = public_path().'/uploads/'.$img->image;
            File::delete($filename);
            Gallery::where('id', $img->id)->delete();
        }

        return back()->with('success','Producto eliminado con éxito!');
    }

    public function uploadfile($fileInput, $id, $id_user)
    {
        //Obtenemos fecha y hora.
        $datetime = new DateTime();
        $ano = $datetime->format('Y');
        $mes = $datetime->format('m');
        $carpeta1 = public_path() . '/uploads/' . $ano;

        //Verificamos que la carpeta del año exista de lo contrario la creamos.
        if (!file_exists($carpeta1)):
            File::makeDirectory($carpeta1, 0755, true, true);
        endif;

        //Verificamos que la carpeta del mes exista de lo contrario la creamos.
        $carpeta2 = public_path() . '/uploads/' . $ano . '/' . $mes;
        if (!file_exists($carpeta2)):
            File::makeDirectory($carpeta2, 0755, true, true);
        endif;

        //Obtenemos la ruta.
        $path = $ano . '/' . $mes . '/';
        //Obtenemos el nombre del archivo.
        $file = $fileInput->getClientOriginalName();
        //Obtenemos la extención del archivo.
        $extension = $fileInput->getClientOriginalExtension();
        $existe = public_path() . '/uploads/' . $ano . '/' . $mes . '/' . $file;
        $new_file = '';
        $i = 1;

        //Verificamos que el nombre del archivo no exista en la carpeta, de lo contrario le agregamos un numero ascendente hasta encontrar un nombre no utilizado.
        while (file_exists($existe)):
            $filename = pathinfo($file, PATHINFO_FILENAME); 
            $new_file = $filename . '-' . $i . '.' . $extension; 
            $existe = public_path() . '/uploads/' . $ano . '/' . $mes . '/' . $new_file; 
            $i++;  
        endwhile;

        if( $new_file != '' ):
            $file = $new_file;
        endif;

        $image_url = $path . $file;

        $nombre  = $file;

        //Subimos el archivo a la carpeta uploads que se encuentra en public.
        $fileInput->move(public_path() . '/uploads/' . $path, $file);

        //Registramos la imagen en la Base de datos..
        Gallery::create([
            'user_id' => $id_user,
            'product_id' => $id,
            'image' => $image_url
        ]);
    }
}