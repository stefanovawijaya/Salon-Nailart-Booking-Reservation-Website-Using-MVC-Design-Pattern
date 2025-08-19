<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tanpa Judul')</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body::-webkit-scrollbar { display: none; }
        body { font-family: 'Inter'; }
    </style>
</head>
<body>

<div class="container-top">
    <img src="{{ asset('img/logoApp.png') }}" style="height: 80px; padding-left: 60px; padding-top: 10px">
    <div class="account-image">
        <div class="name-email-account">
            <p style="font-size: 24px; font-weight: bold; margin-top: 19px;">{{ $salon->salon_name ?? '-' }}</p>
            <p style="font-size: 16px;">{{ $salon->salon_email ?? '-' }}</p>
        </div>
        @if (!empty($salon->salon_image) && file_exists(public_path('img/salon/' . $salon->salon_image)))
            <img src="{{ asset('img/salon/' . $salon->salon_image) }}"
                alt="Salon Image"
                style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; margin-top: -22px !important">
        @else
            <i class="fa fa-circle-user" style="font-size: 50px; color: rgba(255, 229, 242, 1);"></i>
        @endif
    </div>
</div>

<div class="side-nav">
    <a class="{{ request()->is('salon/home') ? 'active' : '' }}" href="{{ route('salon.home') }}">
        <i class="fa fa-home"></i>&emsp;Beranda
    </a>

    <a class="{{ request()->is('salon') || request()->is('salon/account') ? 'active' : '' }}" href="{{ route('salon.account') }}">
        <i class="fa-solid fa-shop"></i>&emsp;Akun
    </a>
    
    <a class="{{ request()->is('salon/schedule*') ? 'active' : '' }}" href="{{ route('salon.schedule.form') }}">
        <i class="fa-solid fa-calendar-days"></i>&emsp;Jadwal
    </a>

    <a class="{{ request()->is('salon/client*') ? 'active' : '' }}" href="{{ route('salon.clients') }}">
        <i class="fa-regular fa-user"></i>&emsp;Klien
    </a>

    <form action="{{ url('/salon/logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="log-out">
            <span class="fa-solid fa-arrow-right-from-bracket"></span>
        </button>
    </form>

</div>

<div class="main-content p-4">
    @yield('content')
</div>

@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-...isi_dari_bootstrap..." crossorigin="anonymous"></script>
</body>
</html>
