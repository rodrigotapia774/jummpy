@extends('layouts.body')

@section('content')

    <div class="pb-12 pt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="text-1xl sm:text-2xl lg:text-3xl leading-none font-extrabold tracking-tight text-gray-900 mb-4 sm:mb-6">{{$producto->name}} <a href="{{ url('/market') }}" class="btn btn-orange float-right"><i class="fas fa-long-arrow-alt-left"></i> Volver a Market</a></h3>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
            <div class="bg-white p-3">
            	<div class="row">
            		<div class="col-12 col-sm-5">
            			<div class="product__image__view">
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
            		</div>
            		<div class="col-12 col-sm-7">
            			<div class="content__product__view">
            				<h3>{{$producto->name}}</h3>
            				<hr>
            				<h4><strong>Proveedor:</strong> {{$producto->proveedor->name}}</h4>
            				<h4><strong>Categoría:</strong> {{$producto->category->name}}</h4>
            				<h4><strong>Stock:</strong> {{number_format($producto->total, 0, ",", ".")}}</h4>
            				<p class="mb-3">{{$producto->resume}}</p>
            				<div class="d-flex justify-content-between">
            					<div class="col-12 col-sm-6 mb-2">
            						<input type="number" name="cantidad" min="1" max="{{$producto->total}}" class="form-control" value="1">
            					</div>
            					<div>
            						<buttom type="submit" class="btn btn-primary">Agregar</buttom>
            					</div>
            				</div>
            			</div>
            		</div>
            		<div class="col-12 mt-5">
            			<p>{{$producto->content}}</p>
            		</div>
                </div>
            </div>
        </div>
    </div>
    
@endsection