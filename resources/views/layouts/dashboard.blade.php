<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mazer Admin Dashboard</title>
    @include('includes.style')
    @stack('css')
</head>

<body>
    <script src="{{ asset('dashboard') }}/static/js/initTheme.js"></script>
    <div id="app">
        <div id="sidebar">
            @include('includes.sidebar')
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            @yield('content')

            @include('includes.footer')
        </div>
    </div>
    @include('includes.script')
    @stack('js')
</body>

</html>
