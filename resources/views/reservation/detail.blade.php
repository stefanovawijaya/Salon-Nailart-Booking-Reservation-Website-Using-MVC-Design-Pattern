@extends('layouts.reservation')

@section('title', 'Detail Janji Salon')

@push('styles')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<style>
    .container-status-klien {
        padding: 5px 10px;
        border-radius: 4px;
        color: white;
        font-weight: bold;
        font-size: 12px;
        margin-left: auto;
        margin-top: 20px;
    }
</style>
@endpush

@section('content')
<div class="isi">
    <div class="tombol-kembali">
        <a href="{{ route('reservation.history') }}">&lt;&emsp;Kembali</a> 
    </div>
    
    <div class="bagian-atas">
        <h4>Detail Janji</h4>
    </div>
    
    <div class="background-detail">
        <div class="detail-janji-salon">
            <img src="{{ asset('img/salon/' . $reservation->salon->salon_image) }}" style="height: 73px; width: 73px;"> 
            <p id="namaSalon">{{ $reservation->salon->salon_name }}</p>
            <p id="alamatSalon">{{ $reservation->salon->salon_location }}</p>
            <hr style="border: 1px solid rgba(255, 112, 180, 1); opacity: 1;">
            
            <h4 style="margin-top: 25px;">DETAIL JANJI</h4>
            <p style="font-size: 14px; font-weight: bold; line-height: 1; display: block; text-align: left;">Tanggal & Waktu Pesanan</p>
            
            <div class="detail-janji-container">
                <div class="detail-janji-salon-detail">
                    <p>Nama Pemesan</p>
                    <p>Status Pesanan</p>
                    <p>Tanggal Pesanan</p>
                    <p>Waktu Pesanan</p>
                </div>

                <div class="detail-janji-salon-titik-dua">
                    <p>:</p>
                    <p>:</p>
                    <p>:</p>
                    <p>:</p>
                </div>

                <div class="detail-janji-salon-isi">
                    <p id="namaPemesan">{{ $reservation->client->client_name }}</p>
                    <p>Bayar ditempat</p>
                    <p id="tanggalPesanan">{{ $reservation->reservation_date->format('d M Y') }}</p>
                    <p id="waktuPesanan">{{ $reservation->created_at->format('H:i') }}</p>
                </div>
            </div>

            <br>
            <p style="font-size: 14px; font-weight: bold; line-height: 1; display: block; text-align: left;">Detail Pesanan</p> 
            
            <div class="detail-janji-container">
                <div class="detail-janji-salon-detail">     
                    <p>Jadwal Pesanan</p>
                    <p>Waktu Dijadwalkan</p>
                    <p>Layanan Pesanan</p>
                </div>
                
                <div class="detail-janji-salon-titik-dua">
                    <p>:</p>
                    <p>:</p>
                    <p>:</p>
                </div>
                
                <div class="detail-janji-salon-isi">
                    <p id="jadwalPesanan">{{ $reservation->reservation_date->format('d M Y') }}</p>
                    <p id="waktuDijadwalkan">{{ $timeLabel }}</p>
                    <div id="layananPesanan">
                        @foreach ($reservation->treatments as $treatment)
                            <p>{{ $treatment->treatment_name }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="container-detail-layanan">
                <div class="detail-layanan" style="width: 45%;">
                    Detail Layanan
                </div>

                <div class="box-harga" style="width: 35%;">
                    Harga
                </div>
            </div>
            
            <div style="display: flex;">
                <div style="display: inline-block; text-align: left; line-height: 0.5; margin-top: 10px;">
                    @foreach ($reservation->treatments as $treatment)
                        <p>{{ $treatment->treatment_name }}</p>
                    @endforeach
                    <p>Voucher Diskon</p>
                    <p>Pajak Layanan</p>
                </div>

                <div style="display: inline-block; margin-left: auto; text-align: right; line-height: 0.5; margin-top: 10px;">
                    @foreach ($reservation->treatments as $treatment)
                        <p>Rp {{ number_format($treatment->treatment_price, 2, ',', '.') }}</p>
                    @endforeach
                    <p>
                        @if($reservation->voucher_value > 0)
                            - Rp {{ number_format($reservation->voucher_value, 2, ',', '.') }}
                        @else
                            Rp 0,00
                        @endif
                    </p>
                    <p>Rp 0,00</p>
                </div>
            </div>

            <div class="container-harga">
                <div class="total-biaya-akhir" style="width: 45%; font-size: 14px;">
                    Total Biaya Akhir
                </div>

                <div class="jumlah-biaya-akhir" style="width: 35%; font-size: 14px;">
                    Rp {{ number_format($reservation->reservation_total_price, 2, ',', '.') }}
                </div>
            </div>
            
            @php
                $status = trim($reservation->reservation_status);
            @endphp

            <div style="display: flex; margin-top: 15px;">
                <p style="display: inline-block; font-size: 14px; margin-top: 25px;">Status Layanan</p>
                <p id="statusBadge" class="container-status-klien">
                    {{ $status }}
                </p>
            </div>
        </div>

        <div class="tombol-pesan" style="margin-top: 30px;">
            <button onclick="location.href='{{ url('/') }}'">Kembali ke Beranda</button>
            <a href="{{ route('reservation.history') }}">Cek Riwayat Pesanan</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/detailreservationclient.js') }}"></script>
@endpush