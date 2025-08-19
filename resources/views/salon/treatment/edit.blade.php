@extends('layouts.salon')

@section('title', 'Edit Treatment')

@section('content')
<style>
input[type=number] {
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
    <a href="{{ route('salon.account') }}" style="color: rgba(255, 112, 180, 1); text-decoration: none;">&lt;  Kembali</a>
</div>

<div class="content-akun">
    @if ($errors->any())
        <div class="alert" style="margin-bottom: 15px; background: #ffe0e9; color: #cc004c; padding: 10px; border-radius: 10px;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <h2 class="fw-bold">Edit Layanan</h2>

    <form action="{{ route('treatment.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="treatment_id" value="{{ $treatment->treatment_id }}">

        <div class="foto-objek">
            <img id="previewImage" src="{{ asset('img/treatment/' . $treatment->treatment_image) }}" style="width: 345px; height: 345px; object-fit: cover;"><br>
            <input type="file" name="treatment_image" id="treatment_image" style="display: none;" accept="image/*">
            <button type="button" onclick="document.getElementById('treatment_image').click()">Unggah Foto</button>
        </div>

        <div class="form-informasi">
            <div class="form-informasi-postingan">Informasi Layanan</div>
            <div class="form-informasi-isi">
                <label for="treatmentName" style="font-size: 20px;">Nama Layanan</label>
                <input type="text" id="treatmentName" name="treatment_name" required value="{{ $treatment->treatment_name }}">

                <label for="treatmentPrice" style="font-size: 20px;">Harga</label><br>
                <input type="number" id="treatmentPrice" name="treatment_price" required value="{{ $treatment->treatment_price }}">

                <div class="button-edit" style="display: flex; gap: 10px; margin-top: 15px;">
                    <input type="submit" value="Simpan">
                    <button type="button" class="delete-button" onclick="submitDelete()">Hapus</button>
                </div>
            </div>
        </div>
    </form>

    <form id="deleteForm" action="{{ url('/treatment/delete') }}" method="post" style="display: none;">
        @csrf
        <input type="hidden" name="treatment_id" value="{{ $treatment->treatment_id }}">
    </form>
</div>

<script>
    document.getElementById('treatment_image').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            const preview = document.getElementById('previewImage');
            preview.src = URL.createObjectURL(file);
        }
    });
</script>

<script>
function submitDelete() {
    if (confirm('Yakin ingin menghapus treatment ini?')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endsection
