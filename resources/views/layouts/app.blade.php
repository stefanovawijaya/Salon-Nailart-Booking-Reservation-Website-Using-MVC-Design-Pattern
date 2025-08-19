<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tanpa Judul')</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @stack('styles')
    <style>
        .navbar { background-color: #FF70B4; }
        .logo, .profile { margin: 20px; }
        .profile { float: right; }
        body::-webkit-scrollbar { display: none; }
        .nav-link { color: #FFFFFF; }
        img { border: none; }
        body {
        width: 430px;
        margin: 0 auto;
        background-color: #ffe5f2;
        font-family: 'Inter';
        }
        .top-bar {
        background-color: #FF70B4;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        }
        .top-bar img { border: 2px solid white; object-fit: cover; }
        .top-bar .logo-img, .top-bar .profile-img {
        height: 50px;
        width: 50px;
        object-fit: cover;
        }
        .top-bar .profile-img {
        border-radius: 50%;
        border: 2px solid white;
        }
    </style>
</head>
<body>
    <div class="top-bar">
    <img src="{{ asset('img/logoApp.png') }}" alt="Logo" class="logo-img" style="border: none;">
    <!-- @php
        use Illuminate\Support\Facades\Auth;

        $client = null;
        if (Auth::guard('client')->check()) {
            $client = Auth::guard('client')->user();
        }
    @endphp -->
    <a href="{{ route('client.profile') }}" class="me-2">
      <img src="{{ asset('img/profile/' . ($client?->client_image_icon ?? 'default.jpg')) }}" 
        alt="Profile" class="profile-img">
    </a>
  </div>

  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/gallery') }}">Galeri</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/salons') }}">Salon</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('reservation.history') }}">Riwayat</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main>
    @yield('content')
  </main>
<script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
@stack('scripts')
</body>
</html>