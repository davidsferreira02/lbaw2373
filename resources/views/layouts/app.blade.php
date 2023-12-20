<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>

        <style>
            /* Custom CSS to enlarge the logo */
            header img {
                width: 400px; /* Adjust the width as needed */
                height: auto; /* Maintain aspect ratio */
                border-radius: 0;
            }
        </style>

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
                @if(Auth::check())
                @if(Auth::user()->isAdmin())
                <a class="nav-link" href="{{ url('/admin') }}">
                    <img src="{{ asset('img/logo.svg') }}" width="300" height="75">
                </a>        
                @endif
                @if(!Auth::user()->isAdmin())
                <a class="nav-link" href="{{ url('/home') }}">
                    <img src="{{ asset('img/logo.svg') }}" width="300" height="75">
                </a>  
                @endif
                @endif
              
                @if (Auth::check())
                    <a class="button" href="{{ url('/logout') }}"> Logout </a> <a href="{{ route('profile', ['id' => Auth::user()->id]) }}" class="btn btn-primary">
                        <img src="{{ Auth::user()->getProfileImage() }}" alt="Profile Image" style="width: 50px; height: 50px;">
                       {{ Auth::user()->username }}
                    </a>
                    </a>
                    
                    
                    <form class="form-inline my-2 my-lg-0 ml-auto" id="search" action="{{ route('search.users') }}" method="GET">
                        <input class="form-control mr-sm-2" type="search" id="searchInput" name="search" placeholder="Search for users or projects">
                        <i class="fas fa-search" id="searchIcon" style="cursor: pointer; color: black;"></i> <!-- Ícone de lupa -->
                    </form>
                    @else
    <!-- Se o usuário não estiver autenticado, mostra o link para o login -->
    <a class="nav-link" href="{{ url('/login') }}">
        <img src="{{ asset('img/logo.svg') }}" width="300" height="75">
    </a>
@endif
               
            </header>
            <section id="content">
                @yield('content')
            </section>
        </main>
        <footer class="footer">
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

<script>
    window.addEventListener("scroll", function() {
        var footer = document.querySelector(".footer");
        // Verifica se a posição vertical do scroll é maior que zero
        if (window.scrollY > 0) {
            footer.style.display = "block"; // Mostra o footer
        } else {
            footer.style.display = "none"; // Esconde o footer se o scroll estiver no topo
        }
    });
</script>