<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UPCV</title>
    <link rel="icon" type="image/png" href="{{asset('asset/img/logo.png')}}"/>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/general.css') }}">

@livewireStyles
<!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ mix('js/dashboard.js') }}" defer></script>
    @stack('css')

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed font-sans antialiased">
<div class="wrapper">
@inject('menu','App\Models\Admin\MenuUrl')

<!-- Navbar -->
@livewire('navigation-menu')
<!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-purple elevation-1">
        <!-- Brand Logo -->
        <a href="{{route('dashboard')}}" class="brand-link text-center active">
            UPCV
        </a>
        <!-- Sidebar -->
        <div class="sidebar " onclose="true" >
            <nav class="mt-2" >
                <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" id="principal" data-accordion="true">
                    <li class="nav-header">MENÚ PRINCIPAL</li>
                    @foreach ($menu->getMenu() as $key=>$item)
                        @if ($item['father']!=0)
                            @break
                        @endif
                        @include("layouts.menu-item",compact('item'))
                    @endforeach
                </ul>
            </nav>
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col">
                        <h1>{{ $header }}</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content" id="content">
            <div class="container-fluid">
                <div class="row" id="app">
                    <div class="col">
                        {{ $slot }}
                    </div>
                    @if (isset($aside))
                        <div class="col-lg-3">
                            {{ $aside }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b><a href="https://upcv.gob.gt/" target="_blank">Unidad Para La Prevención Comunitaria De La Violencia</a></b>
        </div>
        <strong>Powered by</strong> <a href="">System Edbar</a>
    </footer>
</div>

@stack('modals')
@livewireScripts
<script src="{{ asset('js/general.js') }}" defer></script>
@stack('scripts')

</body>
</html>
