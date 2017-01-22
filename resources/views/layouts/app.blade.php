<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Money Manager</title>

        <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
    </head>
    <body>
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand" href="{{ url('/') }}">
                        Money Manager
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="{{ url('/transactions') }}">Transactions</a></li>
                        <li><a href="{{ url('/tags') }}">Tags</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        @if (session('success'))
            <div class="container">
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('danger'))
            <div class="container">
                <div class="alert alert-danger">
                    {{ session('danger') }}
                </div>
            </div>
        @endif

        @yield('content')

        @stack('scripts')
    </body>
</html>
