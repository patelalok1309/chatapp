<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel - Websockets </title>
    @vite('resources/css/app.css')
</head>

<body class="antialiased">

    <a class="mx-auto text-center " href="{{ route('register')}}">Register</a>

    <script src="{{ asset('build/assets/app-CTaZfV6j.js') }}"></script>
    <script>
        Echo.channel('check')
            .listen('TestEvent' , function(e){
                console.log(e);
            })
    </script>
</body>

</html>
