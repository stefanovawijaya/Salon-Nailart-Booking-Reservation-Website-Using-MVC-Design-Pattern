@extends('layouts.salon')

@section('title', 'Tambah Voucher')

@section('content')
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
    <h2 class="fw-bold">Voucher Diskon</h2>
    <div class="foto-objek">
        <img id="previewImage" src="" alt="Preview Gambar" style="width: 345px; height: 345px; object-fit: cover;">
        <br>
        <button type="button" onclick="document.getElementById('voucher_image').click()">Unggah Foto</button>
    </div>
    <div class="form-informasi">
        <div class="form-informasi-postingan">Informasi Postingan</div>
        <div class="form-informasi-isi">
            <form action="{{ route('voucher.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="error-messages">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li style="color: red;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <label for="voucher_code" style="font-size: 20px;">Kode Voucher</label>
                <input type="text" id="voucher_code" name="voucher_code" placeholder="Contoh: EX78DP" required>

                <label for="voucher_value" style="font-size: 20px;">Value Voucher</label>
                <input type="number" id="voucher_value" name="voucher_value" placeholder="Contoh: 20000" required>

                <input type="file" name="voucher_image" id="voucher_image" accept="image/*" style="display: none;" required>

                <div class="button-edit" style="display: flex; gap: 10px; margin-top: 20px;">
                    <input type="submit" value="Simpan">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById("voucher_image").addEventListener("change", function(e) {
    const reader = new FileReader();
    reader.onload = function(event) {
        document.getElementById("previewImage").src = event.target.result;
    };
    reader.readAsDataURL(e.target.files[0]);
});
</script>
@endsection
