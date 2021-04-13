@extends('layouts.body')

@section('content')

    <div class="pb-12 pt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="text-1xl sm:text-2xl lg:text-3xl leading-none font-extrabold tracking-tight text-gray-900 mb-4 sm:mb-6">Nuevo producto <a href="{{ url('/productos') }}" class="btn btn-orange float-right"><i class="fas fa-long-arrow-alt-left"></i> Volver a productos</a></h3>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-3">
                <div clas="row">
                    <form method="POST" class="row needs-validation" action="{{ Route('productos/create') }}" enctype="multipart/form-data" novalidate>
                        <div class="col-12 col-sm-8 col-md-8 mb-3">

                            <!-- Visualizar los mensajes de errors  -->
                            @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            @if ($message = Session::get('success'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                {{ $message }}.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            <!-- Agregar Certificado Token, esto es obligación en cualquier formulario -->
                            @csrf

                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" id="name" name="name" class="form-control" autocomplete="off" required>
                                <div class="invalid-feedback">
                                    Agregue un nombre valido.
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="category" class="form-label">Categoría</label>
                                        <select class="form-select" id="category" name="category">
                                            @foreach($categorys as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Seleccione una categoría valida.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="cantidad" class="form-label">Cantidad</label>
                                        <input type="text" id="cantidad" name="cantidad" class="form-control allNumer" autocomplete="off" required>
                                        <div class="invalid-feedback">
                                            Agregue una cantidad valida.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="valor" class="form-label">Valor</label>
                                        <input type="text" id="valor" name="valor" class="form-control allNumer" autocomplete="off" required>
                                        <div class="invalid-feedback">
                                            Agregue un valor valido.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                        <label for="oferta" class="form-label">Oferta</label>
                                        <input type="text" id="oferta" name="oferta" class="form-control allNumer" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="resumen" class="form-label">Resumen</label>
                                <textarea type="text" id="resumen" name="resumen" class="form-control" autocomplete="off" maxlength="250" required></textarea>
                                <div class="invalid-feedback">
                                    Agregue un resumen valido.
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea type="text" id="descripcion" name="descripcion" class="form-control" autocomplete="off" rows="5" required></textarea>
                                <div class="invalid-feedback">
                                    Agregue una descripción valida.
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        
                        </div>

                        <!-- List Gallery form -->
                        <div class="col-12 col-sm-4 col-md-4 mb-3">
                            <label>Galería</label>
                            <div class="row" id="contentGalery">
                                <div class="col-12 boxgalery mb-2" id="box1" data-number="1">
                                    <input type="file" class="filehidden" onchange="galery(this.files, this.id);" id="galery1" name="galery[]">
                                    <div id="previewGalery1" class="previewGalery"><button type="button" id="1" onClick="presGallery(this.id);" class="btn btn-primary btn-sm"><i class="fas fa-camera-retro"></i> Subir foto</button></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


<script type="text/javascript">
    $('.allNumer').keyup(function (){
    this.value = (this.value + '').replace(/[^0-9/]/g, '');
});
$(".allNumer").on({
    "focus": function (event) {
        $(event.target).select();
    },
    "keyup": function (event) {
        $(event.target).val(function (index, value ) {
            return value.replace(/\D/g, "");
        });
    }
});

function presGallery(id){
        $('#galery' + id).click();
    }

    function changeGallery(id){
        $('#galery' + id).click();
    }

    function aChangeG(i){
        $('#previewGalery'+i).html('');

        var boton = $('<button/>', {
            'type'  : 'button',
            'class' : 'btn-close closeImg',
            'id'    : i,
            'onClick': 'closeGalery(this.id);',
            'aria-label' : 'close'
        });

        $('#previewGalery'+i).append(boton.prop('outerHTML') );

        var boton = $('<button/>', {
            'type'  : 'button',
            'class' : 'changeImg',
            'id'    : i,
            'onClick': 'changeGallery(this.id);',
            'aria-label' : 'edit',
            'html': '<i class="fas fa-camera-retro"></i>',
        });

        $('#previewGalery'+i).append(boton.prop('outerHTML') );
    }

function galery(e, i) {

    i = i.replace("galery", "");

    aChangeG(i);

    // Creamos el objeto de la clase FileReader
    let reader = new FileReader();

    // Leemos el archivo subido y se lo pasamos a nuestro fileReader
    reader.readAsDataURL(e[0]);

    // Le decimos que cuando este listo ejecute el código interno
    reader.onload = function(){
        let preview = document.getElementById('previewGalery'+i),
            image = document.createElement('img');

        image.src = reader.result;
        image.id = 'img'+i;
        preview.append(image);
    };

    addRegisterGalery(i);
        
    $('#galery' + i).attr('onchange','editGalery(this.files, this.id);');
}

function editGalery(e, i) {

    i = i.replace("galery", "");

    // Creamos el objeto de la clase FileReader
    let reader = new FileReader();

    // Leemos el archivo subido y se lo pasamos a nuestro fileReader
    reader.readAsDataURL(e[0]);

    $('#img'+i).remove();

    // Le decimos que cuando este listo ejecute el código interno
    reader.onload = function(){
        let preview = document.getElementById('previewGalery'+i),
            image = document.createElement('img');

        image.src = reader.result;
        image.id = 'img'+i;
        preview.append(image);
    };
}

function addRegisterGalery(i) {
    aux =  Number.parseInt(i) + 1;

    var html = '';
    html += '<div class="col-12 col-sm-6 boxgalery mb-2" id="box'+aux+'" data-number="'+aux+'">';
    html += '<input type="file" class="filehidden" onchange="galery(this.files, this.id);" id="galery'+aux+'" name="galery[]">';
    html += '<div id="previewGalery'+aux+'" class="previewGalery">';
    html += '<button type="button" id="'+aux+'" onClick="presGallery(this.id);" class="btn btn-primary btn-sm"><i class="fas fa-camera-retro"></i> Subir foto</button>';
    html += '</div>';
    html += '</div>';

    $('#contentGalery').append(html);
}

function closeGalery(e) {
    $('#box'+e).remove();
}


(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})();
</script>

@endsection