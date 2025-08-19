@extends('layouts.salon')

@section('title', 'Galeri Desain')

@section('content')
<div class="back-button">
    <a href="{{ route('salon.account') }}" style="color: rgba(255, 112, 180, 1); text-decoration: none;">&lt; Kembali</a>
</div>

<div class="content-akun">
    <h2 class="fw-bold">Galeri Desain</h2>

    <div class="foto-objek">
        <img id="previewImage" src="" alt="Preview Gambar" style="width: 345px; height: 345px; object-fit: cover;">
        <br>
        <button type="button" onclick="document.getElementById('galleryImage').click()">Unggah Foto</button>
    </div>

    <div class="form-informasi">
        <div class="form-informasi-postingan">Informasi Postingan</div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-informasi-isi">
            <form action="{{ route('salon.gallery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="galleryName" style="font-size: 20px;">Nama Galeri Desain</label>
                <input type="text" id="galleryName" name="gallery_name" placeholder="Contoh: Cat Eye Nail" required>

                <label for="galleryDesc" style="font-size: 20px;">Deskripsi Galeri Desain</label>
                <textarea id="galleryDesc" name="gallery_desc" placeholder="Contoh: Rp. 500.000 - Nail Art Deco Cat Eye" required></textarea>

                <input type="file" name="gallery_image" id="galleryImage" accept="image/*" style="display: none;" required onchange="previewImage(event)">

                <div class="button-edit">
                    <input type="submit" value="Simpan">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById("galleryImage").addEventListener("change", function(e) {
    const reader = new FileReader();
    reader.onload = function(event) {
        document.getElementById("previewImage").src = event.target.result;
    };
    reader.readAsDataURL(e.target.files[0]);
});
</script>
@endsection
