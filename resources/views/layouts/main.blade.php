<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title')</title>


    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet"> 

    <!-- Styles -->
    <link href="{{ url('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/0.4.5/sweet-alert.css">
    <link rel="stylesheet" type="text/css" href="{{asset('/bower_components/admin-lte/plugins/datatables/dataTables.customLoader.walker.css')}}">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <script type="text/javascript">
           var base_url    = "{{ url('/') }}";
   </script>

</head>
<body>

    <div id="app">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ url('js/jquery.min.js') }}"></script>
    <script src="{{ url('js/bootstrap.min.js') }}"></script>
    <script src="{{ url('js/custom.js') }}"></script>
    <script src="{{asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.js")}}"></script>
    <script src="{{asset ("/bower_components/admin-lte/plugins/datatables/dataTables.bootstrap.js")}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@latest/dist/sweetalert2.all.js"></script>
    @stack('scripts')
</body>
</html>
