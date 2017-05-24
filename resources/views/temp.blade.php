<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content cont-a temp">
                <div class="container ">
                   <div class="row">
                        <div class=col-sm-3>
                            <img src="/img/tempLogo.png" class= 'img-responsive'>
                        </div>
                        <div class="col-sm-9">
                            <div class="title-a">A világ legnépszerűbb kávézói!</div>
                            <div class="paragraph-a">A Tied már köztük van?
                                
                            </div>
                            <div class="paragraph-b">
                            Coffee Guest hamarosan.
                                
                            </div>                            
                               
                        </div>
                   </div>
                </div>
            </div>
        </div>
    </body>
</html>
