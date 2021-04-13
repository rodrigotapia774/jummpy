<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Services;
use App\Models\Proveedores;
use App\Models\Categoryser;
use App\Models\Galleryser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Providers\RouteServiceProvider;
use DateTime;
use File;

class ServiciosController extends Controller
{
    /**
     * Create a new controller instance with authenticate.
     *
     * @return void
     */
    public function __construct()
    {
        /**Solicitamos que el usuario este registrado para continuar*/
        $this->middleware('auth');
    }

    /**
     * Show the application services list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // Todos los servicios con estado Activos (1).
      $servicios = Services::where('state', 1)->paginate(10);

      //Se llama a la vista "lista" ruta ./resources/views/services/lista.
      return view('services.lista')->with('servicios', $servicios);
    }

    /**
     * Show the application product list inactive.
     *
     * @return \Illuminate\Http\Response
     */
    public function inactive()
    {
      //Todos los productos con estado Activos (1) y paginamos los resultados de 20 en 20.
      $servicios = Services::where('state', 2)->orderBy('created_at', 'DESC')->paginate(20);

      //Se llama a la vista "lista" ruta ./resources/views/services/lista.
      return view('services.lista')->with('servicios', $servicios);
    }

    /**
     * Show the application services list search result.
     *
     * @return \Illuminate\Http\Response
     */
    public function search($buscar = null)
    {
        // Todos los servicios con estado Activos (1) y utilizamos pagiate para paginar resultados de 20 en 20.
        $servicios = Services::where('name','like', '%' . $buscar . '%')->orderBy('created_at', 'DESC')->paginate(20);

        //Se llama a la vista "lista" ruta ./resources/views/services/lista.
        return view('services.lista')->with('servicios', $servicios);
    }

    /**
     * Mostramos el formulario para el nuevo registro de servicio.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Todos las categorías con estado Activos (1).
        $categorys = Categoryser::where('state', 1)->get();

        //Se llama a la vista de "registro" ruta ./resources/views/services/create.
        return view('services.create')->with('categorys', $categorys);
    }

    /**
     * Validamos e insertamos los datos entregados por el formulario.
     *
     * @param  Request  $request
     * @return Response
     */
    public function insert(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:250',
            'category' => 'required',
            'cierre' => 'required|date',
            'valor' => 'required|numeric',
            'entrega' => 'nullable|date',
            'resumen' => 'required|max:250',
            'descripcion' => 'required',
            'galery.*' => 'image'
        ]);

        //Obteemos el Id de la compañia del usuario.
        $id_company = Auth::user()->company_id;

        //Obetenemos el Id del usuario.
        $id_user = Auth::user()->id;

        //Registramos el servicio y obtenemos los datos del nuevo registro.
        $service = Services::create([
            'user_id' => $id_user,
            'proveedor_id' => $id_company,
            'category_id' => $request->category,
            'name' => $request->name,
            'resume' => $request->resumen,
            'content' => $request->descripcion,
            'price' => $request->valor,
            'cierre' => $request->cierre,
            'entrega' => $request->entrega,
            'state' => 1
        ]);

        //Id del nuevo registro de servicio.
        $id = $service->id;

        //Verificamos que exista el campo galery y que no se encuetre vacio.
        if(isset($request->galery) && !empty($request->galery)): 
            //Recorremos los resultados de gallery.
            foreach($request->galery as $fileInput):

              //Enviamos la imagen a la función uploadfile para sser subida.
              $this->uploadfile($fileInput, $id, $id_user);

            endforeach;

        endif;

        //Volvemos al formulario de registro, con un mensje de éxito.
        return back()->with('success','Servicio agregado con éxito!');
    }

    /**
     * Mostramos un formulario con los datos del servicio seleccionado.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $id = Crypt::decrypt($id);

      $id_company = Auth::user()->company_id;

      $datos = Services::with(['gallery', 'category'])->where('id', $id)->where('proveedor_id', $id_company)->first();
      //Todos los productos con estado Activos (1).
      $categorys = Categoryser::where('state', 1)->get();

      //Se llama a la vista "lista" ruta ./resources/views/ventas/productos/create.
      return view('services.update')->with('categorys', $categorys)->with('datos', $datos);
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
            'cierre' => 'required|date',
            'valor' => 'required|numeric',
            'entrega' => 'nullable|date',
            'resumen' => 'required|max:250',
            'descripcion' => 'required',
            'estado' => 'required',
            'galery.*' => 'image'
        ]);

        $id = Crypt::decrypt($request->id_oculto);

        $id_company = Auth::user()->company_id;

        $id_user = Auth::user()->id;

       Services::where('id', $id)->where('proveedor_id', $id_company)->update(
            [
                'category_id' => $request->category,
                'name' => $request->name,
                'resume' => $request->resumen,
                'content' => $request->descripcion,
                'price' => $request->valor,
                'cierre' => $request->cierre,
                'entrega' => $request->entrega,
                'state' => $request->estado
            ]
        );

       //Obtenemos las imagenes del servicio para recorrer, comparar y eliminar si no se encuetra similitud con los registros de la galería del formulario.
       $imagenes = Galleryser::where('service_id', $id)->get();

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
                Galleryser::where('id', $img->id)->delete();
            }
        }

        //Recorremos las nuevas imagenes agregadas al formulario y se almacenan en la Base de datos y en la carpeta publica.
        if(isset($request->galery) && !empty($request->galery)){
            foreach($request->galery as $fileInput){

                $this->uploadfile($fileInput, $id, $id_user);

            }

        }

        //Volvemos al formulario con un mensaje de éxito.
        return back()->with('success','Servicio modificado con éxito!');
    }

    /**
     * Desactivamos el servicio dejandolo en estado 2.
     *
     * @return \Illuminate\Http\Response
     */
    public function desactive($id)
    {
        $id = Crypt::decrypt($id);

        $id_company = Auth::user()->company_id;

        //Se realizan 2 verificaciones para desactivar el servicio.  1) el Id de servicio. 2) el proveedor del servicio debe igual al id del proveedor del cliente.
        Services::where('id', $id)->where('proveedor_id', $id_company)->update(
            [ 
                'state' => 2
            ]
        );

        return back()->with('success','Servicio desactivado con éxito!');
    }

    /**
     * Activamos el servicio dejandolo en estado 1.
     *
     * @return \Illuminate\Http\Response
     */
    public function active($id)
    {
        $id = Crypt::decrypt($id);

        $id_company = Auth::user()->company_id;

        //Se realizan 2 verificaciones para activar el servicio. 1) el Id de servicio. 2) el proveedor del producto sea igual al id del proveedor del cliente.
        Services::where('id', $id)->where('proveedor_id', $id_company)->update(
            [ 
                'state' => 1
            ]
        );

        return back()->with('success','Servicio activado con éxito!');
    }

    /**
     * Eliminamos el servicio y sus imagenes.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $id = Crypt::decrypt($id);

        $id_company = Auth::user()->company_id;

        //Se realizan 2 verificaciones para eliminar el producto. 1) el Id de producto. 2) el proveedor del producto sea igual al id del proveedor del cliente.
        Services::where('id', $id)->where('proveedor_id', $id_company)->delete();

        $imagenes = Galleryser::where('service_id', $id)->get();

        foreach($imagenes as $img){
            $filename = public_path().'/uploads/'.$img->image;
            File::delete($filename);
            Galleryser::where('id', $img->id)->delete();
        }

        return back()->with('success','Servicio eliminado con éxito!');
    }

    /**
     * Validamos y submimos las imagenes al servidor.
     *
     */
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

        //Verificamos que el nombre del archivo no exista en la carpeta, de lo contrario le agregamos un numero ascendente.
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

        //Subimos el archivo a la carpeta.
        $fileInput->move(public_path() . '/uploads/' . $path, $file);

        //Registramos la imagen en la Base de datos.
        Galleryser::create([
            'user_id' => $id_user,
            'service_id' => $id,
            'image' => $image_url
        ]);
    }
}