@extends('layouts.body')

@section('content')

    <div class="pb-12 pt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="text-1xl sm:text-2xl lg:text-3xl leading-none font-extrabold tracking-tight text-gray-900 mb-4 sm:mb-6">Productos <a href="{{ url('/productos/create') }}" class="btn btn-orange float-right"><i class="far fa-plus-square"></i> Crear producto</a></h3>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="row">
                <div class="col-12 col-sm-3">
                    <div class="display-block mx-auto bg-white p-4">
                    	<h3 class="text-lg font-extrabold">Categorías</h3>
                    	@foreach($categorys as $category)
                    	<a href="{{URL::asset('market/category') }}/{{ Crypt::encrypt($category->id) }}">
                    		<p class="pl-3 mb-2 text-gray-800 hover:text-yellow-600">{{$category->name}}</p>
                    	</a>
                    	@endforeach
                    </div>
                </div>
                <div class="col-12 col-sm-9">
                	<div class="row">
                		<div class="col-12 mb-3">
                            <div class="display-block mx-auto bg-white p-2">
                    	        <div class="input-group">
                                    <input type="text" id="search" class="form-control form-control-sm border-0" placeholder="Search" aria-label="Recipient's username" aria-describedby="button-addon2">
                                    <button class="btn btn-outline-secondary btn-sm border-0" type="button" id="buscar"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>

                        @foreach($productos as $producto)
                        <div class="col-12 col-sm-4 mb-3">
                            <div class="box__product">
                            	<div class="product__image">
                            		<div id="carousel{{$producto->id}}" class="carousel slide" data-bs-ride="carousel" data-bs-touch="true" data-bs-interval="false">
                                        <div class="carousel-inner">
                                        	<?php $aux = 1;?>
                                        	@foreach($producto->gallery as $gallery)
                                            <div class="carousel-item <?php if($aux == 1){ echo 'active';}?>">
                                                <div class="flex__img ">
                                                	<img src="{{ asset('uploads/' . $gallery->image) }}" class="d-block w-100" alt="...">
                                                </div>
                                            </div>
                                            <?php $aux++;?>
                                            @endforeach
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{$producto->id}}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carousel{{$producto->id}}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                            	</div>
                            	<a href="{{URL::asset('market/view') }}/{{ Crypt::encrypt($producto->id) }}" class="product__content">
                            		<h3>{{$producto->name}}</h3>
                            		<p>
                            			{{substr($producto->resume, 0, 60) . '...'}}
                            		</p>
                            		<div class="d-flex justify-content-between">
                            			@if($producto->sale != '')
                            			<div>
                            				<h2>$ {{ number_format($producto->sale, 0, ",", ".") }}</h2>
                            			</div>
                            			<div>
                            				<h4>$ {{ number_format($producto->price, 0, ",", ".") }}</h4>
                            			</div>
                            			@else
                            			<div>
                            				<h2>$ {{ number_format($producto->price, 0, ",", ".") }}</h2>
                            			</div>
                            			@endif
                            		</div>
                            		<p>Vendido por: {{$producto->proveedor->name}}</p>
                            	</a>
                            </div>
                        </div>
                        @endforeach

                        <div class="col-12 mt-3 mb-3">
                        	{{ $productos->onEachSide(5)->links() }}
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