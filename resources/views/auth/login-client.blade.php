@extends('layouts.auth')

@section('title', 'Login Klien')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100 px-5" style="background-color: #FCFBFB;">
    
<form action="{{ route('login.client') }}" method="POST" class="px-4" style="max-width: 400px; width: 100%;">
    @csrf

    <div class="text-center mb-4">
        <img src="{{ asset('img/logo-circle.png') }}" alt="Logo" style="width: 100px;">
    </div>
    
    <div class="text-start mb-4">
        <h5 class="fw-normal mb-1">Selamat Datang,</h5>
        <h4 class="fw-bold text-dark">Klien Salon!</h4>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-3">
        <label for="email" class="form-label">Alamat Email</label>
        <input type="email" class="form-control rounded-3 px-3 py-2" id="client_email" name="client_email"
               placeholder="janedoe@gmail.com" value="{{ old('client_email') }}" required>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Kata Sandi</label>
        <input type="password" class="form-control rounded-3 px-3 py-2" id="client_password" name="client_password"
               placeholder="*************" required>
    </div>

    <div class="mb-4">
        <small class="d-block mb-2">
            Belum mempunyai akun?<br>
            <a href="{{ route('register.client') }}" class="text-decoration-none fw-medium" style="color: rgba(255, 112, 180, 1);">
                Buat Akun &rsaquo;
            </a>
        </small>
        <br>
        <small>
            Bukan Klien Salon?<br>
            <a href="{{ route('login.salon') }}" class="text-decoration-none fw-medium" style="color: rgba(255, 112, 180, 1);">
                Ke halaman Pengelola Salon &rsaquo;
            </a>
        </small>
    </div>

    <button type="submit" class="btn w-100 text-white fw-bold"
            style="background-color: #AE65D4; border-radius: 30px; padding: 10px 0;">
        Masuk Akun
    </button>
</form>
</div>
@endsection