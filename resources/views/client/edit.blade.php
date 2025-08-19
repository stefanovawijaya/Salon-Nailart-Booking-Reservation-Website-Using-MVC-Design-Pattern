@extends('layouts.client')

@section('title', 'Edit Profil')

@section('content')
<div class="container-fluid min-vh-100 px-3" style="background-color: #FF70B4; padding-top: 1rem;">

    <nav class="navbar">
        <div class="d-flex align-items-center">
            <a href="{{ route('client.profile') }}" class="text-white text-decoration-none">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </nav>

    <h5 class="text-white fw-bold text-center mb-4">Edit Akun</h5>

    @if (empty($client->client_name) || empty($client->client_phonenumber))
        <div class="alert alert-warning">Lengkapi profil Anda terlebih dahulu sebelum melakukan reservasi.</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form action="{{ route('client.update') }}" method="POST" enctype="multipart/form-data" class="text-center">
        @csrf

        <div class="mb-3">
            <img id="previewImage"
                src="{{ asset('img/profile/' . ($client->client_image_icon ?? 'default.jpg')) }}"
                alt="Foto Profil"
                class="img-thumbnail"
                style="width: 200px; height: 230px; object-fit: cover; border-radius: 10px;">
        </div>

        <div class="mb-4">
            <label class="btn"
                style="background-color: #AE65D4; color: white; border-radius: 25px; padding: 8px 24px; width: 200px">
                Unggah Foto
                <input type="file" name="client_image_icon" id="client_image_icon" accept="image/*" hidden>
            </label>
        </div>

        <div class="bg-white text-start p-4 rounded-top-5 shadow-sm">
            <h6 class="fw-bold mb-3 text-center text-dark">Informasi Akun</h6>

            <div class="mb-3">
                <label for="client_name" class="form-label">Nama Akun</label>
                <input type="text" name="client_name" id="client_name" class="form-control border border-pink"
                    value="{{ old('client_name', $client->client_name) }}" placeholder="Contoh: Jane Doe" required>
            </div>

            <div class="mb-4">
                <label for="client_phonenumber" class="form-label">Nomor Telepon</label>
                <input type="text" name="client_phonenumber" id="client_phonenumber" class="form-control border border-pink"
                    value="{{ old('client_phonenumber', $client->client_phonenumber) }}" placeholder="Contoh: 08123456789" required pattern="[0-9]+" inputmode="numeric">
            </div>

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
            @endif

            <input type="hidden" name="client_id" value="{{ $client->client_id }}">
            <input type="hidden" name="current_image_icon" value="{{ $client->client_image_icon }}">

            <div class="text-center">
                <button type="submit" class="btn text-white"
                    style="background-color: #AE65D4; padding: 10px 30px; border-radius: 25px;">
                    Simpan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const inputFile = document.getElementById('client_image_icon');
    const previewImage = document.getElementById('previewImage');

    inputFile.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
            }
            reader.readAsDataURL(file);
        } else {
            previewImage.src = "{{ asset('img/profile/' . ($client->client_image_icon ?? 'default.jpg')) }}";
        }
    });
</script>
@endpush