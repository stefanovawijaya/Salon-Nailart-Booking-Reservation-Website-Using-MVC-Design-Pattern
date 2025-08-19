@extends('layouts.salon')

@section('title', 'Tambah Treatment')

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
  <a href="{{ route('salon.account') }}" style="color: rgba(255, 112, 180, 1); text-decoration: none;">&lt;  Kembali</a>  
</div>

<div class="content-akun">
    <h2 class="fw-bold">Daftar Harga Treatment</h2>

    <div class="foto-objek">
        <img id="previewImage" src="" alt="Preview Gambar" style="width: 345px; height: 345px; object-fit: cover;">
        <br>
        <button type="button" onclick="document.getElementById('treatment_image').click()">Unggah Foto</button>
    </div>
    
    <div class="form-informasi">
        <div class="form-informasi-postingan">
            Informasi Postingan
        </div>
        <div class="form-informasi-isi">
            <form action="{{ route('treatment.store') }}" method="POST" enctype="multipart/form-data">
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

                <div>
                    <label for="treatment_name" style="font-size: 20px;">Nama Treatment</label>
                    <input type="text" name="treatment_name" id="treatment_name" placeholder="Contoh: Manicure" required>
                </div>

                <div>
                    <label for="treatment_price" style="font-size: 20px;">Harga</label><br>
                    <input type="number" name="treatment_price" id="treatment_price" placeholder="Contoh: 100000" required>
                </div>

                <div>
                    <input type="file" name="treatment_image" id="treatment_image" accept="image/*" style="display: none;" required>
                </div>

                <div class="button-edit">
                    <input type="submit" value="Simpan">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById("treatment_image").addEventListener("change", function(e) {
    const reader = new FileReader();
    reader.onload = function(event) {
        document.getElementById("previewImage").src = event.target.result;
    };
    reader.readAsDataURL(e.target.files[0]);
});
</script>
@endsection
