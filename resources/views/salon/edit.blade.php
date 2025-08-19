@extends('layouts.salon')

@section('title', 'Edit Salon')

@section('content')
<div class="back-button">
    <a href="{{ route('salon.account') }}" style="color: rgba(255, 112, 180, 1); text-decoration: none;">&lt; Kembali</a>  
</div>

<div class="content-akun">
    <h2 class="fw-bold">Edit Akun</h2>

    <div class="foto-objek">
        <img id="previewImage" 
             src="{{ asset('img/salon/' . ($salon->salon_image ?? 'default.jpg')) }}" 
             style="width: 345px" 
             alt="Salon Profile Image">

        <br>
        <button type="button" onclick="document.getElementById('salon_image').click();">Unggah Foto</button>
    </div>

    <div class="form-informasi">
        <div class="form-informasi-postingan">Informasi Akun</div>

        <div class="form-informasi-isi">
            <form action="{{ route('salon.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')

                @if ($errors->any())
                    <div class="error-messages">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li style="color: red;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <input type="file" id="salon_image" name="salon_image" accept="image/*" style="display: none;" onchange="previewSelectedImage(event)">
                <input type="hidden" name="current_image" value="{{ $salon->salon_image ?? 'default.jpg' }}">

                <label for="salon_name" style="font-size: 20px;">Nama Akun</label>
                <input type="text" id="salon_name" name="salon_name" 
                       value="{{ old('salon_name', $salon->salon_name ?? '') }}" 
                       placeholder="Contoh: Nail Art by Jenna">

                <label for="salon_description" style="font-size: 20px;">Deskripsi Akun</label>
                <input type="text" id="salon_description" name="salon_description" 
                       value="{{ old('salon_description', $salon->salon_description ?? '') }}" 
                       placeholder="Contoh: Cantik itu mudah. Mulai dari ujung jari Anda.">

                <label for="salon_operational_hour" style="font-size: 20px;">Jam Operasional</label>
                <input type="text" id="operationalHours" name="salon_operational_hour" 
                       value="{{ old('salon_operational_hour', $salon->salon_operational_hour ?? '') }}" 
                       placeholder="Contoh: 09:00 - 20:00">

                <label for="salon_location" style="font-size: 20px;">Lokasi Salon</label>
                <input type="text" id="salon_location" name="salon_location" 
                       value="{{ old('salon_location', $salon->salon_location ?? '') }}" 
                       placeholder="Contoh: Jl. Lorem Ipsum, Jakarta, Indonesia">

                <label for="salon_phonenumber" style="font-size: 20px;">Nomor Telepon</label>
                <input type="text" id="salon_phonenumber" name="salon_phonenumber" 
                       value="{{ old('salon_phonenumber', $salon->salon_phonenumber ?? '') }}" 
                       placeholder="Contoh: 082345784532">

                <label for="salon_pinpoint" style="font-size: 20px;">Titik Lokasi sesuai Google Maps (sertakan link)</label>
                <input type="text" id="salon_pinpoint" name="salon_pinpoint" 
                       value="{{ old('salon_pinpoint', $salon->salon_pinpoint ?? '') }}" 
                       placeholder="Contoh: https://maps.app.goo.gl/3vSq5rhbw5UtvAaG7">

                <input type="submit" value="Simpan">
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/validateupdatesalon.js') }}"></script>
@endsection