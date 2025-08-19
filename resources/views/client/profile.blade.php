@extends('layouts.client')

@section('title', 'Profil Klien')

@section('navbar')
<nav class="navbar">
    <div class="d-flex align-items-center">
        <a href="{{ route('home') }}" class="text-white text-decoration-none">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</nav>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success text-center mx-3 alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

        </div>
    @endif
    <div class="text-center mb-3">
        <img
            src="{{ asset('img/profile/' . ($client->client_image_icon ?? 'default.jpg')) }}"
            alt="Foto profil {{ $client->client_name ?? 'Pengguna' }}"
            class="rounded-circle mb-2 profile-image"
            style="width:90px; height:90px; object-fit:cover; border:2px solid white;"
        >
        <h5 class="fw-bold mb-0">{{ $client->client_name ?: '-' }}</h5>
        <small>
            Bergabung sejak
            {{ $client->created_at ? $client->created_at->format('d/m/y') : '-' }}
        </small>
    </div>

    <div class="bg-white text-dark rounded-3 p-3 mb-4 text-start shadow-sm">
        <p class="mb-2"><i class="fas fa-phone me-2"></i>{{ $client->client_phonenumber ?: '-' }}</p>
        <p class="mb-0"><i class="fas fa-envelope me-2"></i>{{ $client->client_email ?? '-' }}</p>
    </div>

    <div class="d-grid gap-3 mb-4">
        <a href="{{ url('/client/edit') }}" class="btn btn-light d-flex align-items-center shadow-sm rounded-3 px-4 py-2" style="background-color:#FFE5F2;">
            <i class="fas fa-user-edit me-3 text-dark"></i> <span class="text-dark">Ubah Akun</span>
        </a>
        <a href="{{ url('/gallery') }}" class="btn btn-light d-flex align-items-center shadow-sm rounded-3 px-4 py-2" style="background-color:#FFE5F2;">
            <i class="fas fa-ticket-alt me-3 text-dark"></i> <span class="text-dark">Buat Pesanan</span>
        </a>
        <a href="{{ route('reservation.history') }}" class="btn btn-light d-flex align-items-center shadow-sm rounded-3 px-4 py-2" style="background-color:#FFE5F2;">
            <i class="fas fa-clock me-3 text-dark"></i> <span class="text-dark">Riwayat Pesanan</span>
        </a>
    </div>

    <div class="text-center mt-auto">
        <form method="POST" action="{{ url('/client/logout') }}">
            @csrf
            <button type="submit" class="btn" style="background-color:#AE65D4; border-radius:15px; padding:10px 14px;" aria-label="Logout" title="Logout">
                <i class="fas fa-sign-out-alt fa-lg text-white"></i>
            </button>
        </form>
    </div>
@endsection