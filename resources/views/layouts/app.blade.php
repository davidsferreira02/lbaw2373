<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
        <script src="https://js.pusher.com/7.0/pusher.min.js" defer></script>

    </head>
    <body>
        <main>
            <header>
                <h1><a href="{{ url('/home') }}">TaskSquad</a></h1>
                @if (Auth::check())
                    <a class="button" href="{{ url('/logout') }}"> Logout </a> <a href="{{ route('profile', ['id' => Auth::user()->id]) }}" class="btn btn-primary">
                        {{ Auth::user()->name }}
                    </a>
                    
                    <form class="form-inline my-2 my-lg-0 ml-auto" id="search" action="{{ route('search.users') }}" method="GET">
                        <input class="form-control mr-sm-2" type="search" id="searchInput" name="search" placeholder="Search for users or projects">
                        <i class="fas fa-search" id="searchIcon" style="cursor: pointer;"></i> <!-- Ícone de lupa -->
                    </form>
                    
                    
                @endif
            </header>
            <section id="content">
                @yield('content')
            </section>
        </main>
        <footer>
            <div class="container">
                <ul>
                    <li><a href="{{ route('about') }}">About Us</a></li>

                    <li><a href={{ url('/features') }}>Main Features</a></li>       
                    <li><a href={{ url('/contacts') }}>Contacts</a></li>                           
                   
                </ul>
            </div>
        </footer>
    </body>

    <!-- resources/views/footer.blade.php -->



</html>


<script>
    document.getElementById('searchIcon').addEventListener('click', function() {
        document.getElementById('search').submit();
    });
</script>