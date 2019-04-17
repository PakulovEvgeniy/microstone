<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ URL::to('css/app-glob.css') }}" />
        <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    </head>
    <body>
        <div id="app">
            @include('layouts.header')

            @section('sidebar')
              Это главная боковая панель
            @show

            <div class="container">
                @yield('content')
            </div>

            @include('layouts.footer')
        </div>
        <script src="{{ URL::to('js/entry-client.js') }}"></script>
    </body>
</html>