@extends('layouts.salon')

@section('title', 'Edit Voucher')

@section('content')
@if(session('voucher_error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('voucher_error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('voucher_success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('voucher_success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<style>
input[type=number]{
    display: block;
    width: 100%;
    height: 56px;
    box-sizing: border-box;
    margin: 8px 0px;
    padding: 12px 20px;
    border-radius: 10px;
    border: 2px solid rgba(255, 112, 180, 1);
    font-size: 20px;
}
</style>

<div class="back-button">
    <a href="{{ route('salon.account') }}" style="color: rgba(255, 112, 180, 1); text-decoration: none;">&lt; Kembali</a>
</div>

<div class="content-akun">
    <h2 class="fw-bold">Edit Voucher</h2>

    <form action="{{ route('voucher.update') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="voucher_id" value="{{ $voucher->voucher_id }}">
        <input type="hidden" name="old_image" value="{{ $voucher->voucher_image }}">

        <div class="foto-objek">
            <img id="previewImage" src="{{ asset('img/voucher/' . $voucher->voucher_image) }}" 
                 style="width: 345px; height: 345px; object-fit: cover;"><br>

            <input type="file" name="voucher_image" id="voucher_image" style="display: none;" accept="image/*">
            <button type="button" onclick="document.getElementById('voucher_image').click()">Unggah Foto</button>
        </div>

        <div class="form-informasi">
            <div class="form-informasi-postingan">
                Informasi Voucher
            </div>
            <div class="form-informasi-isi">
                <label for="voucher_code" style="font-size: 20px;">Kode Voucher</label>
                <input type="text" id="voucher_code" name="voucher_code" required value="{{ $voucher->voucher_code }}">

                <label for="voucher_value" style="font-size: 20px;">Nilai Voucher (mis. 10%)</label>
                <input type="number" id="voucher_value" name="voucher_value" required value="{{ $voucher->voucher_value }}">

                <div class="button-edit" style="display: flex; gap: 10px; margin-top: 15px;">
                    <input type="submit" value="Simpan">

                    <button type="button" class="delete-button" data-id="{{ $voucher->voucher_id }}">Hapus</button>
                </div>
            </div>
        </div>
    </form>

    <form id="deleteForm" action="{{ route('voucher.delete') }}" method="post" style="display: none;">
        @csrf
        <input type="hidden" name="voucher_id" value="{{ $voucher->voucher_id }}">
    </form>
</div>

<script>
    document.getElementById('voucher_image').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            const preview = document.getElementById('previewImage');
            preview.src = URL.createObjectURL(file);
        }
    });
</script>

<script>
document.querySelector('.delete-button')?.addEventListener('click', function() {
    if (confirm('Yakin ingin menghapus voucher ini?')) {
        document.getElementById('deleteForm').submit();
    }
});
</script>
@endsection
