@extends('layouts.reservation')

@section('title', 'Buat Reservasi')

@section('content')
<div class="isi">
  <div class="tombol-kembali">
    <a onclick="javascript:window.history.back();">&lt;&emsp;Kembali</a>
  </div>

  <div class="bagian-atas">
    <h4>Buat Pesanan</h4>
    <img src="{{ asset('img/salon/' . $salon->salon_image) }}" class="info-row">
    <h4>{{ $salon->salon_name }}</h4>
    <p>{{ $salon->salon_location }}</p>
  </div>

  <div class="pesanan">
    @auth('client')
    
    @if ($errors->any())
        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="reservationForm" action="{{ route('reservation.store') }}" method="POST">
      @csrf
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <input type="hidden" name="client_id" value="{{ auth()->guard('client')->id() }}">
      <input type="hidden" name="salon_id" value="{{ $salon->salon_id }}">
      <input type="hidden" name="reservation_date" id="selectedDateInput" value="{{ old('reservation_date') }}">
      <input type="hidden" name="reservation_start" id="selectedStartTime" value="{{ old('reservation_start') }}">
      <input type="hidden" name="reservation_end" id="selectedEndTime" value="{{ old('reservation_end') }}">

      <label for="namaPemesan" style="font-size: 16px;">Nama Pemesan</label>
      <input type="text" name="namaPemesan" value="{{ $client->client_name ?? '' }}" readonly>

      <label for="pilihanLayanan" style="font-size: 16px;">Pilihan Layanan</span></label>
      <div class="layanan-container">
        <div class="dropdown-box" onclick="toggleDropdown()">
          <span class="dropdown-placeholder">Layanan yang diinginkan</span>
          <span class="dropdown-icon">▾</span>
        </div>
        <div class="dropdown-list" id="layananDropdown">
          @foreach ($treatments as $treatment)
          <label class="dropdown-item">
            <span class="layanan-nama">{{ $treatment->treatment_name }}</span>
            <span class="layanan-harga">Rp {{ number_format($treatment->treatment_price, 0, ',', '.') }}</span>
            <input type="checkbox" name="treatments[]" value="{{ $treatment->treatment_id }}" 
                   data-price="{{ $treatment->treatment_price }}"
                   {{ in_array($treatment->treatment_id, old('treatments', [])) ? 'checked' : '' }}>
          </label>
          @endforeach
        </div>
      </div>

      <label style="font-size: 16px;">Pilih Tanggal</span></label>
      <div class="calendar-container-klien">
        <div class="month-nav-klien">
          <button type="button" onclick="changeMonthKlien(-1)">&lt;</button>
          <p id="monthYearKlien" style="font-size: 20px; font-weight: 600; line-height: 0.1;"></p>
          <button type="button" onclick="changeMonthKlien(1)">&gt;</button>
        </div>
        <hr style="border: 1px solid rgba(255, 112, 180, 1); width: 100%; opacity: 1;">
        <div class="kalender-hari-klien">
          <p>S</p><p>S</p><p>R</p><p>K</p><p>J</p><p>S</p><p>M</p>
        </div>
        <hr style="border: 1px solid rgba(255, 112, 180, 1); width: 100%; opacity: 1;">
        <div class="calendar-klien" id="calendarKlien"></div>
      </div>

      <hr style="border: 1px solid rgba(255, 112, 180, 1); margin-top: 20px; opacity: 1;">
      <p style="font-weight: bold; font-size: 16px; margin-left: 5px;">Pilih Slot</span></p>
      <hr style="border: 1px solid rgba(255, 112, 180, 1); opacity: 1;">
      <div class="tombol-slot"></div>

      <div class="form-kupon">
        <img src="{{ asset('img/kupon.png') }}">
        <input type="text" id="formKupon" name="formKupon" placeholder="Silakan masukkan kode promo" value="{{ old('formKupon', request('voucher_code')) }}">
        <div id="voucher-message" style="margin-top: 5px; color: green; font-size: 0.9em;"></div>
        <button type="button" class="validateButton" onclick="validateVoucher()">Gunakan Kupon</button>

        <button type="button" class="removeVoucherButton" onclick="removeVoucher()">Hapus Voucher</button>
      </div>

      <div class="total-payment">
        <img src="{{ asset('img/tabler-icon-coin.png') }}">
        <p style="font-size: 16px;">Total Payment:</p>
        <p class="payment-akhir">Rp 0</p>
      </div>

      <div class="tombol-pesan">
        <button type="submit">Lakukan Pesanan</button>
        <a onclick="javascript:window.history.back();">Batalkan Pesanan</a>
      </div>
    </form>
    @else
      <p>Silakan login terlebih dahulu untuk melakukan reservasi.</p>
    @endauth
    <hr style="border: none; margin-top: 20px; opacity: 1;">
  </div>
</div>

<div id="popup-flash" style="display: none;"
     data-success="{{ session('reservation_success') ? '1' : '0' }}"
     data-error="{{ session('reservation_error') ?? ($errors->first('error') ?? '') }}">
</div>

@endsection

@push('scripts')
<script>
  const BASEURL = "{{ url('') }}";
  const SALON_ID = "{{ $salon->salon_id }}";
</script>
<script src="{{ asset('js/reservationclient.js') }}"></script>
<script src="{{ asset('js/validatereservation.js') }}"></script>
@include('reservation.partials.styles')
@endpush