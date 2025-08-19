@extends('layouts.salon')

@section('title', 'Detail Janji Klien')

@push('styles')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="back-button">
    <a href="javascript:history.back()" style="color: rgba(255, 112, 180, 1); text-decoration: none;">&lt; Kembali</a>  
</div>

<div class="content-janji">
    <h2 style="display: inline-block;" class="fw-bold">Detail Janji Klien</h2>

    <select class="status-select">
        <option value="Selesai" {{ $reservation['reservation_status'] === 'Selesai' ? 'selected' : '' }}>Selesai</option>
        <option value="SedangBerlangsung" {{ $reservation['reservation_status'] === 'Sedang Berlangsung' ? 'selected' : '' }}>Sedang Berlangsung</option>
        <option value="Dibatalkan" {{ $reservation['reservation_status'] === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
    </select>
</div>

<div class="content" style="margin-top: -230px;">
    <div class="detail-janji">
        <h4 class="fw-bold" style="text-align: center; font-size: 20px;">PESANAN</h4>
        <img src="{{ asset('img/profile/' . ($reservation->client->client_image_icon ?? 'default.jpg')) }}" style="height: 73px; width: 73px;">
        <p class="fw-bold mt-2" style="font-size: 16px;">{{ $reservation->client->client_name }}</p>
        <p>{{ $reservation->client->client_email }}</p>
        <p>{{ $reservation->client->client_phonenumber }}</p>

        <hr style="border: 1px solid rgba(255, 112, 180, 1);">

        <h4 class="fw-bold mt-4">DETAIL JANJI</h4>
        <p class="fw-bold mt-3 mb-3">Tanggal & Waktu Pesanan</p>

        <div class="detail-janji-container-luar">
            <div class="detail-janji-container" style="width: 67%;">
                <div class="detail-janji-salon-detail">
                    <p>Nama Pemesan</p>
                    <p>Status Pesanan</p>
                </div>
                <div class="detail-janji-salon-titik-dua">
                    <p>:</p>
                    <p>:</p>
                </div>
                <div class="detail-janji-salon-isi2">
                    <p>{{ $reservation->client->client_name }}</p>
                    <p>Bayar ditempat</p>
                </div>
            </div>
            <div class="detail-janji-container" style="width: 30%;">
                <div class="detail-janji-salon-detail">
                    <p>Tanggal Pesanan</p>
                    <p>Waktu Pesanan</p>
                </div>
                <div class="detail-janji-salon-titik-dua">
                    <p>:</p>
                    <p>:</p>
                </div>
                <div class="detail-janji-salon-isi2">
                    <p>{{ \Carbon\Carbon::parse($reservation['reservation_created_at'])->translatedFormat('d F Y') }}</p>
                    <p>{{ \Carbon\Carbon::parse($reservation['reservation_created_at'])->format('H:i') }}</p>
                </div>
            </div>
        </div>

        <p class="fw-bold mt-4 mb-3">Detail Pesanan</p>

        <div class="detail-janji-container-luar">
            <div class="detail-janji-container" style="width: 67%;">
                <div class="detail-janji-salon-detail">
                    <p>Jadwal Pesanan</p>
                    <p>Waktu Dijadwalkan</p>
                </div>
                <div class="detail-janji-salon-titik-dua">
                    <p>:</p>
                    <p>:</p>
                </div>
                <div class="detail-janji-salon-isi2">
                    <p>{{ \Carbon\Carbon::parse($reservation['reservation_date'])->translatedFormat('d F Y') }}</p>
                    <p>{{ \Carbon\Carbon::parse($reservation['reservation_start'])->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation['reservation_end'])->format('H:i') }}</p>
                </div>
            </div>
            <div class="detail-janji-container" style="width: 30%;">
                <div class="detail-janji-salon-detail">
                    <p>Layanan Pesanan</p>
                </div>
                <div class="detail-janji-salon-titik-dua">
                    <p>:</p>
                </div>
                <div class="detail-janji-salon-isi2">
                    @foreach ($treatments as $treatment)
                        <p>{{ $treatment['treatment_name'] }}</p>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="container-detail-layanan">
            <div class="detail-layanan">Detail Layanan</div>
            <div class="box-harga">Harga</div>
        </div>

        <div style="display: flex;">
            <div style="text-align: left; line-height: 0.5; margin-top: 10px;">
                @foreach ($treatments as $treatment)
                    <p>{{ $treatment['treatment_name'] }}</p>
                @endforeach
                <p>Voucher Diskon</p>
                <p>Pajak Layanan</p>
            </div>
            <div style="margin-left: auto; text-align: right; line-height: 0.5; margin-top: 10px;">
                @foreach ($treatments as $treatment)
                    <p>Rp {{ number_format($treatment['treatment_price'], 2, ',', '.') }}</p>
                @endforeach
                <p>- Rp {{ number_format($reservation->voucher_value, 2, ',', '.') }}</p>
                <p>Rp 0,00</p>
            </div>
        </div>

        <div class="container-harga">
            <div class="total-biaya-akhir">Total Biaya Akhir</div>
            <div class="jumlah-biaya-akhir">
                Rp {{ number_format($reservation['reservation_total_price'], 2, ',', '.') }}
            </div>
        </div>
    </div>

    <input type="hidden" id="reservationId" value="{{ $reservation['reservation_id'] }}">
    <input type="hidden" id="reservationStatus" value="{{ $reservation['reservation_status'] }}">

    <div id="popupKonfirmasi" style="display: none; position: fixed; top: 0; left: 0; 
        width: 100%; height: 100%; background: rgba(0,0,0,0.5); 
        justify-content: center; align-items: center; z-index: 9999;">
        <div style="font-size: 24px; background: white; padding: 20px; border-radius: 10px; text-align: center;">
            <p>Apakah Anda yakin ingin mengubah status?</p>
            <button onclick="konfirmasiStatus(true)" style="font-size: 16px; background-color:rgba(255, 112, 180, 1); border: none; color: white; border-radius: 8px; margin-right: 10px; width: 100px; height: 30px;">Ya</button>
            <button onclick="konfirmasiStatus(false)" style="font-size: 16px; background-color: #ddd; border: none; border-radius: 8px; width: 100px; height: 30px;">Tidak</button>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/detailreservationsalon.js') }}"></script>
@endpush
@endsection
