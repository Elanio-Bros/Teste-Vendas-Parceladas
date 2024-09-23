<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <link href="{{ asset('css/bootstrap/bootstrap.css') }}"rel="stylesheet">
    <link href="{{ asset('css/fontawesome/all.css') }}"rel="stylesheet">
    @yield('head-content')
</head>

<body>
    <div class="container-fluid">
        @yield('body')
    </div>
    @yield('modal')
</body>
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/bootstrap/bootstrap.bundle.js') }}"></script>
<script href="{{ asset('js/fontawesome/all.js') }}"></script>
@yield('script')

</html>
