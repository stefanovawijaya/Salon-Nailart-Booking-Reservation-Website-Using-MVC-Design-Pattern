<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Tanpa Judul')</title>

    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
        .navbar {
            background-color: #FF70B4;
            padding: 1rem;
        }
        body::-webkit-scrollbar {
            display: none;
        }
        body {
            width: 430px;
            background-color: #FF70B4;
            margin-left: auto;
            margin-right: auto;
            font-family: 'Inter';
        }
    </style>
</head>
<body>

    @yield('navbar')

    <div class="container-fluid min-vh-100">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
