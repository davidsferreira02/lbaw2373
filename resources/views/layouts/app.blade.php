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
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src="{{ url('js/app.js') }}" defer>
        </script>

    </head>
    <body>
        <main>
            @if (Auth::check())
                <nav class="navbar navbar-expand-lg bg-body-tertiary" style="background-color: #ffffff">
                    <div class="nav nav-pills nav-fill">
                        <a class="nav-link" href="{{ url('/home') }}">
                            <img src="images/logo.svg" width="300" height="75">
                        </a>
                        <form class="d-flex" role="search" action="{{ route('search.users') }}" method="GET">
                            <input class="form-control me-2" type="text" name="search" placeholder="Search for users or projects" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                        <a class="nav-link" href="{{ url('/myprojects') }}">Projects</a>
                        <a class="nav-link" href="#">Timeline</a>
                        <a href="{{ route('profile', ['id' => Auth::user()->id]) }}" class="btn btn-primary ml-2">
                            {{ Auth::user()->name }}
                        </a>
                    </div>
                </nav>
            @endif
            <section id="content">
                @yield('content')
            </section>
        </main>
    </body>
</html>