<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tanpa Judul')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    
    <style>
        body::-webkit-scrollbar {
            display: none;
        }
        body {
            background-color: #FCFBFB;
            width: 430px;
            margin-left: auto;
            margin-right: auto;
            font-family: 'Inter';
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
