@extends('layouts.salon')

@section('title', 'Edit Galeri Desain')

@section('content')
<div class="back-button">
    <a href="{{ route('salon.account') }}" style="color: rgba(255, 112, 180, 1); text-decoration: none;">&lt; Kembali</a>
</div>

<div class="content-akun">
    <h2 class="fw-bold">Edit Galeri Desain</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('salon.gallery.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="gallery_id" value="{{ $gallery->gallery_id }}">

        <div class="foto-objek">
            <img id="previewImage" src="{{ asset('img/gallery/' . $gallery->nailart_image) }}" 
                 style="width: 345px; height: 345px; object-fit: cover;">
            <br>
            <input type="file" name="nailart_image" id="nailart_image" style="display: none;" accept="image/*" onchange="previewImage(event)">
            <button type="button" onclick="document.getElementById('nailart_image').click()">Unggah Foto</button>
        </div>

        <div class="form-informasi">
            <div class="form-informasi-postingan">Informasi Postingan</div>
            <div class="form-informasi-isi">
                <label for="galleryName" style="font-size: 20px;">Nama Galeri Desain</label>
                <input type="text" id="galleryName" name="nailart_name" required value="{{ old('nailart_name', $gallery->nailart_name) }}">

                <label for="galleryDesc" style="font-size: 20px;">Deskripsi Galeri Desain</label>
                <textarea id="galleryDesc" name="nailart_desc" required>{{ old('nailart_desc', $gallery->nailart_desc) }}</textarea>

                <div class="button-edit" style="display: flex; gap: 10px; margin-top: 15px;">
                    <input type="submit" value="Simpan">

                    <button type="button" class="delete-button" onclick="submitDelete()">Hapus</button>
                </div>
            </div>
        </div>
    </form>

    <form id="deleteForm" action="{{ route('salon.gallery.delete') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="gallery_id" value="{{ $gallery->gallery_id }}">
    </form>
</div>

<script>
    document.getElementById('nailart_image').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            const preview = document.getElementById('previewImage');
            preview.src = URL.createObjectURL(file);
        }
    });
</script>
<script>
function submitDelete() {
    if (confirm('Yakin ingin menghapus galeri ini?')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endsection
