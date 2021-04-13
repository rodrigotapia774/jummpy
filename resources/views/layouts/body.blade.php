<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <!-- Add style personaliced -->
        <link rel="stylesheet" href="{{URL::asset('css/structure.css') }}">

        <!-- Add FontAwesome Free -->
        <link href="{{URL::asset('fontawesome/css/all.css')}}" rel="stylesheet">

        <!-- Add Bootstrap 5 Beta -->
        <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

        

        

        <!-- Scripts -->
         <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
         <!-- Jquery Confirm alert event-->
        <link rel="stylesheet" href="{{URL::asset('jqueryConfirm/dist/jquery-confirm.min.css') }}">
        <script src="{{ asset('jqueryConfirm/dist/jquery-confirm.min.js') }}"></script>

        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-jet-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                <div class="flex w-full">
                     <!-- Menú vertical -->
                    <div class="bg-white h-screen">
                         <div class="w-64 navigationMenu">
                            <div class="mb-3 mt-5">
                                 <div class="flex text-dark">
                                     <div class="w-12 ml-1 text-center"><i class="fas fa-shopping-cart"></i></div>
                                     <div class="navItem"><a href="{{ url('/market') }}" class="relative no-underline text-gray-800 hover:text-yellow-600 hover:border-4 border-brand">Market Productos</a></div>
                                 </div>
                             </div>
                             <div class="mb-3">
                                 <div class="flex text-dark">
                                     <div class="w-12 ml-1 text-center"><i class="fas fa-shopping-cart"></i></div>
                                     <div class="navItem"><a href="{{ url('/market-services') }}" class="relative no-underline text-gray-800 hover:text-yellow-600 hover:border-4 border-brand">Market Licitaciones</a></div>
                                 </div>
                             </div>
                             <div class="mb-3">
                                 <div class="flex text-dark">
                                     <div class="w-12 ml-1 text-center"><i class="fas fa-shopping-bag"></i></div>
                                     <div class="navItem"><a href="{{ url('/compras') }}" class="relative no-underline text-gray-800 hover:text-yellow-600 hover:border-4 border-brand">Compras</a></div>
                                 </div>
                             </div>
                             <div class="mb-3">
                                 <div class="flex text-dark">
                                     <div class="w-12 ml-1 text-center"><i class="fas fa-calculator"></i></div>
                                     <div class="navItem"><a href="{{ url('/ventas') }}" class="relative no-underline text-gray-800 hover:text-yellow-600 hover:border-4 border-brand">Ventas</a></div>
                                 </div>
                             </div>
                             <div class="mb-3">
                                 <div class="flex text-dark">
                                     <div class="w-12 ml-1 text-center"><i class="fas fa-desktop"></i></div>
                                     <div class="navItem"><a href="{{ url('/productos') }}" class="relative no-underline text-gray-800 hover:text-yellow-600 hover:border-4 border-brand">Productos</a></div>
                                 </div>
                             </div>
                             <div class="mb-3">
                                 <div class="flex text-dark">
                                     <div class="w-12 ml-1 text-center"><i class="far fa-handshake"></i></div>
                                     <div class="navItem"><a href="{{ url('/servicios') }}" class="relative no-underline text-gray-800 hover:text-yellow-600 hover:border-4 border-brand">Servicios</a></div>
                                 </div>
                             </div>
                         </div>
                    </div>
                    <div class="w-full bg-gray-100">
                        <!-- Cargamos el componente... -->
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
    </body>
</html>
