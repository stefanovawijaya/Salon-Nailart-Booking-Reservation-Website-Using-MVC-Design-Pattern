@extends('layouts.salon')

@section('title', 'Profil Salon')

@section('content')
<style>
.button-edit-salon {
    margin-top: 15px;
    color: white;
    background-color: rgba(174, 101, 212, 1);
    border: none;
    border-radius: 8px;
    padding: 7px 20px;
    font-size: 14px;
    display: block;
    margin-left: auto;
    margin-right: auto;
    cursor: pointer;
}
.right-button {
    display: flex;
    justify-content: flex-end;
    align-items: flex-start;
}
.section-container {
    margin-top: 30px;
}
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
.add-button {
    background-color: rgba(174, 101, 212, 1);
    color: white;
    padding: 8px 15px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 12px;
}
.items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
}
.item-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
}
.empty-state {
    text-align: center;
    padding: 40px;
    color: #999;
    border: 2px dashed #ddd;
    border-radius: 8px;
}
</style>


<div class="content">
    <h2 class="fw-bold">Akun</h2>

    <div class="container-akun">
        <div class="right-button">
            <a href="{{ url('/salon/edit') }}" class="editakun-button">Edit Akun</a>
        </div>

        <img src="{{ asset('img/salon/' . ($salon->salon_image ?? 'default.jpg')) }}" alt="Profile" class="img-akun">
        <p style="text-align: center; margin-top: 10px; font-size: 20px;">{{ $salon->salon_name ?? 'Nama belum diisi' }}</p>
        <p style="font-size: 14px; text-align: center; line-height: 0.3;">{{ $salon->salon_description ?? '-' }}</p>

        <div class="list-data-akun" style="display: flex; justify-content: center;">
            <p style="line-height: 2.5; font-size: 14px;">
                <img src="{{ asset('img/tabler-icon-calendar-clock.png') }}"> {{ $salon->salon_operational_hour ?? 'Jam operasional belum diisi' }}<br>
                <img src="{{ asset('img/tabler-icon-map-pin.png') }}"> {{ $salon->salon_location ?? 'Lokasi belum diisi' }}<br>
                <img src="{{ asset('img/tabler-icon-device-mobile.png') }}"> {{ $salon->salon_phonenumber ?? 'Nomor belum diisi' }}<br>
                <img src="{{ asset('img/tabler-icon-mail.png') }}"> {{ $salon->salon_email }}
            </p>
        </div>
    </div>

    <div class="container-update">
        <div class="container-update-judul">
            Galeri Desain
        </div>
        <div class="container-update1">
            <div class="container-update2">
                @if(session('gallery_success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('gallery_success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('gallery_error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('gallery_error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="container-update3" style="display: flex; flex-wrap: wrap; gap: 20px;">
                    @if(isset($gallery) && count($gallery) > 0)
                        @foreach($gallery as $item)
                            <div style="width: 200px; text-align: center;">
                                @if($item->nailart_image)
                                    <img src="{{ asset('img/gallery/' . $item->nailart_image) }}" 
                                        alt="{{ $item->nailart_name }}" 
                                        style="width: 100%; height: 200px; object-fit: cover; border-radius: 5px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                @else
                                    <div style="width: 100%; height: 200px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999; border-radius: 5px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                        No Image
                                    </div>
                                @endif
                                <a href="{{ route('salon.gallery.edit', $item->gallery_id) }}">
                                    <button class="button-edit-salon">
                                        Edit
                                    </button>
                                </a>
                            </div>
                        @endforeach
                        <div style="width: 200px; text-align: center;">
                            <a href="{{ route('salon.gallery.add') }}">
                                <img src="{{ asset('img/buttonPlus.png') }}" alt="Tambah" 
                                    style="width: 80px; height: 80px; margin-top: 40px;">
                            </a>
                        </div>
                    @else
                        <p>Tidak ada galeri yang tersedia.</p>
                        <div class="container-update3" style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px;">
                            <div style="width: 200px; text-align: center;">
                                <a href="{{ route('salon.gallery.add') }}">
                                    <img src="{{ asset('img/buttonPlus.png') }}" alt="Tambah" 
                                        style="width: 80px; height: 80px;">
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container-update">
        <div class="container-update-judul">
            Daftar Layanan
        </div>
        <div class="container-update1">
            <div class="container-update2">
                @if(session('treatment_success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('treatment_success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('treatment_error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('treatment_error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="container-update3" style="display: flex; flex-wrap: wrap; gap: 20px;">
                    @if(isset($treatments) && count($treatments) > 0)
                        @foreach($treatments as $treatment)
                            <div style="width: 200px; text-align: center;">
                                @if($treatment->treatment_image)
                                    <img src="{{ asset('img/treatment/' . $treatment->treatment_image) }}" 
                                        alt="{{ $treatment->treatment_name }}" 
                                        style="width: 100%; height: 200px; object-fit: cover; border-radius: 5px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                @else
                                    <div style="width: 100%; height: 200px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999; border-radius: 5px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                        No Image
                                    </div>
                                @endif
                                
                                <a href="{{ url('treatment/edit/' . $treatment->treatment_id) }}">
                                    <button class="button-edit-salon">
                                        Edit
                                    </button>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <p>Tidak ada layanan treatment yang tersedia.</p>
                    @endif
                    <div style="width: 200px; text-align: center;">
                        <a href="{{ route('treatment.add') }}">
                            <img src="{{ asset('img/buttonPlus.png') }}" alt="Tambah" 
                                style="width: 80px; height: 80px; margin-top: 40px;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-update">
        <div class="container-update-judul">
            Voucher Diskon
        </div>
        <div class="container-update1">
            <div class="container-update2">
                @if(session('voucher_success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('voucher_success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('voucher_error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('voucher_error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="container-update3" style="display: flex; flex-wrap: wrap; gap: 20px;">
                    @if(isset($vouchers) && count($vouchers) > 0)
                        @foreach($vouchers as $voucher)
                            <div style="width: 200px; text-align: center;">
                                @if($voucher->voucher_image)
                                    <img src="{{ asset('img/voucher/' . $voucher->voucher_image) }}" 
                                        alt="{{ $voucher->voucher_code }}" 
                                        style="width: 100%; height: 200px; object-fit: cover; border-radius: 5px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                @else
                                    <div style="width: 100%; height: 200px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999; border-radius: 5px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                        No Image
                                    </div>
                                @endif
                                
                                {{-- Commented out like in the target view --}}
                                {{-- <h4 style="margin: 10px 0 5px;">{{ $voucher->voucher_code }}</h4>
                                <p style="margin-bottom: 10px;">Rp {{ number_format($voucher->voucher_value, 0, ',', '.') }}</p> --}}
                                
                                <a href="{{ url('voucher/edit/' . $voucher->voucher_id) }}">
                                    <button class="button-edit-salon">
                                        Edit
                                    </button>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <p>Tidak ada voucher yang tersedia.</p>
                    @endif
                    {{-- Plus button to add new voucher --}}
                    <div style="width: 200px; text-align: center;">
                        <a href="{{ route('voucher.add') }}">
                            <img src="{{ asset('img/buttonPlus.png') }}" alt="Tambah" 
                                style="width: 80px; height: 80px; margin-top: 40px;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection