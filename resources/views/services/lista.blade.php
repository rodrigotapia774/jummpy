@extends('layouts.body')

@section('content')

    <div class="pb-12 pt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="text-1xl sm:text-2xl lg:text-3xl leading-none font-extrabold tracking-tight text-gray-900 mb-4 sm:mb-6">Licitaciones <a href="{{ url('/servicios/create') }}" class="btn btn-orange float-right"><i class="far fa-plus-square"></i> Crear licitación</a></h3>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-3">
                <div clas="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <div class="mb-2">
                                <div class="btn-group" role="group" aria-label="Basic outlined example">
                                    <a href="{{URL::asset('servicios') }}" class="btn btn-sm <?php if(request()->routeIs('servicios')): echo'btn-primary'; else: echo'btn-outline-primary'; endif;?>">Activos</a>
                                    <a href="{{URL::asset('servicios-inactive') }}" class="btn btn-sm <?php if(request()->routeIs('servicios-inactive')): echo'btn-primary'; else: echo'btn-outline-primary'; endif;?>">Inactivos</a>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="input-group mb-3">
                                    <input type="text" id="search" class="form-control form-control-sm" placeholder="Buscar por nombre..." aria-label="Recipient's username" aria-describedby="button-addon2">
                                    <button class="btn btn-outline-secondary btn-sm" type="button" id="buscar">Button</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-12 col-sm-12 mb-3">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                {{ $message }}.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Precio</th>
                                    <th scope="col">Ubicación</th>
                                    <th scope="col">Cierre</th>
                                    <th scope="col">Entrega</th>
                                    <th scope="col">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($servicios as $service)
                                <tr>
                                    <td>{{ $service->name }}</td>
                                    <td>$ {{ number_format($service->price, 0, ",", ".") }}</td>
                                    <td>{{ $service->cierre }}</td>
                                    <td>{{ $service->entrega }}</td>
                                    <td>
                                        <a href="{{URL::asset('servicios/update') }}/{{ Crypt::encrypt($service->id)}}" class="btn btn-primary btn-sm">Editar</a>
                                        @if(request()->routeIs('servicios'))
                                        <a href="{{URL::asset('servicios/inactive') }}/{{ Crypt::encrypt($service->id)}}" class="btn btn-warning btn-sm inactivebutton">Desactivar</a>
                                        @endif
                                        @if(request()->routeIs('servicios-inactive'))
                                        <a href="{{URL::asset('servicios/active') }}/{{ Crypt::encrypt($service->id)}}" class="btn btn-info btn-sm inactivebutton">Activar</a>
                                        @endif
                                        <a href="{{URL::asset('servicios/delete') }}/{{ Crypt::encrypt($service->id) }}" class="btn btn-danger btn-sm deletebutton">Eliminar</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12">
                        <div class="col-12">{{ $servicios->onEachSide(5)->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
$(document).ready(function(){
    $('a.inactivebutton').confirm({
        title: 'Desactivar producto!',
        content: "¿Desea desactivar el servicio seleccionado?",
    });
    $('a.inactivebutton').confirm({
        buttons: {
            hey: function(){
                location.href = this.$target.attr('href');
            }
        }
    });

    $('a.deletebutton').confirm({
        title: 'Eliminar producto!',
        content: "¿Desea eliminar el servicio seleccionado?",
    });
    $('a.deletebutton').confirm({
        buttons: {
            hey: function(){
                location.href = this.$target.attr('href');
            }
        }
    });

    $('#buscar').click(function(){
        if($('#search').val() != '') {
            $(location).attr("href", "{{URL::asset('servicios/search') }}/" + $('#search').val());
        } else {
            $('#search').focus();
        }
    });
});
</script>


@endsection