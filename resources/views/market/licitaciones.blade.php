@extends('layouts.body')

@section('content')

    <div class="pb-12 pt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="text-1xl sm:text-2xl lg:text-3xl leading-none font-extrabold tracking-tight text-gray-900 mb-4 sm:mb-6">Licitaciones <a href="{{ url('/servicios/create') }}" class="btn btn-orange float-right"><i class="far fa-plus-square"></i> Crear licitación</a></h3>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="row">
                <div class="col-12">
                	<div class="row">
                		<div class="col-12 mb-3">
                            <div class="display-block mx-auto bg-white p-2">
                    	        <div class="input-group">
                                    <input type="text" id="search" class="form-control form-control-sm border-0" placeholder="Search" aria-label="Recipient's username" aria-describedby="button-addon2">
                                    <button class="btn btn-outline-secondary btn-sm border-0" type="button" id="buscar"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 bg-white p-5">
                        <div class="row">
                        @foreach($licitaciones as $licitacion)
                        <div class="col-12 col-sm-4 mb-3">
                            <div class="box__licitaciones">
                            	<a href="{{URL::asset('market/view') }}/{{ Crypt::encrypt($licitacion->id) }}" class="licitaciones__content">
                                    @if($licitacion->state == 1)
                                    <p class="overflow-hidden"><span class="badge bg-orange float-right">Abierta</span></p>
                            		@else
                                    <p class="overflow-hidden"><span class="badge bg-green float-right">Cerrada</span></p>
                                    @endif
                                    <h3>{{$licitacion->name}}</h3>
                            		<p>
                            			{{substr($licitacion->resume, 0, 60) . '...'}}
                            		</p>
                            		<div class="d-flex justify-content-between">
                                        <div>
                                            <p>Monto estimado:</p>
                                        </div>
                            			<div>
                            				<p class="text-blue">$ {{ number_format($licitacion->price, 0, ",", ".") }}</p>
                            			</div>
                            		</div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>Fecha de publicación:</h5>
                                        </div>
                                        <div>
                                            <h5 class="text-blue">{{ date("d-m-Y", strtotime($licitacion->created_at)) }}</h5>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>Fecha de cierre:</h5>
                                        </div>
                                        <div>
                                            <h5 class="text-blue">{{ $licitacion->cierre}}</h5>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>Fecha de entrega:</h5>
                                        </div>
                                        <div>
                                            <h5 class="text-blue">{{ $licitacion->entrega }}</h5>
                                        </div>
                                    </div>
                                    <?php 
                                    $create = $licitacion->created_at;
                                    $next = $licitacion->cierre;
                                    $now = new DateTime();
                                    $create = $create->format('Y-m-d');
                                    $now = $now->format('Y-m-d');
                                    $date1 = new DateTime($create);
                                    $date2 = new DateTime($next);
                                    $date3 = new DateTime($now);
                                    $diff = $date1->diff($date2);
                                    $diff2 = $date3->diff($date2);
                                    $totalporc = ($diff->days * $diff2->days )/100;
                                    ?>
                                    <div class="progress mt-2">
                                        <div class="progress-bar bg-orange" role="progressbar" style="width: {{$totalporc}}%" aria-valuenow="{{$totalporc}}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                            	</a>
                            </div>
                        </div>
                        @endforeach
                        </div>

                        <div class="col-12 mt-3 mb-3">
                        	{{ $licitaciones->onEachSide(5)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
$(document).ready(function(){
	$('#buscar').click(function(){
        if($('#search').val() != '') {
            $(location).attr("href", "{{URL::asset('market/search') }}/" + $('#search').val());
        } else {
            $('#search').focus();
        }
    });
});
</script>
@endsection