@extends('layouts.auth')

@section('title', 'Login Salon')

@section('content')
<body style="background-color: rgba(255, 229, 242, 1);"> 
<div class="d-flex justify-content-center align-items-center min-vh-100 px-5" style="background-color: rgba(255, 229, 242, 1);">
    <div style="max-width: 400px; width: 100%;">

        <div class="text-center mb-4">
            <img src="{{ asset('img/logo-circle.png') }}" alt="Logo" style="width: 100px;">
        </div>

        <div class="text-start mb-4">
            <h5 class="fw-normal mb-1">Selamat Datang,</h5>
            <h4 class="fw-bold text-dark">Pengelola Salon!</h4>
        </div>

        <form action="{{ route('login.salon.post') }}" method="POST">
            @csrf

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
                <label for="salon_email" class="form-label">Alamat Email</label>
                <input type="email" class="form-control rounded-3 px-3 py-2" id="salon_email" name="salon_email"
                       placeholder="janedoe@gmail.com" value="{{ old('salon_email') }}" required>
            </div>

            <div class="mb-3">
                <label for="salon_password" class="form-label">Kata Sandi</label>
                <input type="password" class="form-control rounded-3 px-3 py-2" id="salon_password" name="salon_password"
                       placeholder="*************" required>
            </div>

            <div class="mb-4">
                <small class="d-block mb-2">
                    Belum mempunyai akun?<br>
                    <a href="{{ route('register.salon') }}" class="text-decoration-none fw-medium" style="color: rgba(255, 112, 180, 1);">
                        Buat Akun &rsaquo;
                    </a>
                </small>
                <br>
                <small>
                    Bukan Pengelola Salon?<br>
                    <a href="{{ route('login') }}" class="text-decoration-none fw-medium" style="color: rgba(255, 112, 180, 1);">
                        Ke halaman Klien Salon &rsaquo;
                    </a>
                </small>
            </div>

            <button type="submit" class="btn w-100 text-white fw-bold"
                    style="background-color: #AE65D4; border-radius: 30px; padding: 10px 0;">
                Masuk Akun
            </button>
        </form>
    </div>
</div>
</body>
@endsection