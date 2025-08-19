@extends('layouts.app')

@section('title', 'Galeri Salon')

@section('content')
<script>
  const BASEURL = "{{ url('/') }}";
</script>

<link href="{{ asset('css/style.css') }}" rel="stylesheet">

<style>
#popupSalon {
  position: fixed;
  bottom: 0;
  left: 50%;
  max-width: 430px;
  margin-left: -215px;
  right: 0;
  background: #fff;
  border-radius: 30px 30px 0 0;
  box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
  padding: 16px 40px;
  display: none;
  z-index: 999;
  align-items: center;
}

.container-box img { 
  border-bottom: 1px solid rgba(255, 112, 180, 1);
  width: 100%;
  height: 130px;
  display: block;
  border-radius: 5px 5px 0 0;
}

#popupNamaSalon{
  margin-top: 8px !important;
}

#popupJamOperasional{
  margin-top: 0px !important;
}
</style>

<div class="isi">
  <div class="background-detail">
    <div class="search-container" style="width: 85%; margin: 0 auto;">
      <input type="text" id="searchInput" onkeyup="searchSalons()" placeholder="Cari nama salon...">
      <i class="fa fa-search"></i>
    </div>

    <div class="container-box1">
      @if ($salons->isNotEmpty())
        @foreach ($salons as $salon)
          <div class="container-box">
            <img src="{{ asset('img/salon/' . $salon->salon_image) }}" alt="Salon Image">
            <p class="nama-salon">{{ $salon->salon_name }}</p>
            <button class="lihat-detail-btn"
              data-nama="{{ $salon->salon_name }}"
              data-gambar="{{ asset('img/salon/' . $salon->salon_image) }}"
              data-jam="{{ $salon->salon_operational_hour }}"
              data-alamat="{{ $salon->salon_location }}"
              data-pinpoint="{{ $salon->salon_pinpoint }}"
              data-salonid="{{ $salon->salon_id }}"
            >Lihat Detail Salon</button>
          </div>
        @endforeach
      @else
        <p>No salons available.</p>
      @endif
    </div>

    <p id="tidakDitemukan">Tidak ditemukan hasil pencarian</p>

    <div id="popupOverlay"></div>

    <div id="popupSalon">
      <div class="popup-header">
        <h3>Detail Salon</h3>
        <button onclick="tutupPopup()">
          <img src="{{ asset('img/tombolClose.png') }}">
        </button>
      </div>

      <hr style="border: 1px solid rgba(255, 112, 180, 1); opacity: 1;">

      <div class="popup-container">
        <img id="popupGambar" src="{{ asset('img/beauty salon square.png') }}">
        <div>
          <div class="popup-margin">
            <p id="popupNamaSalon">Beauty Salon</p>
            <div class="pop-up-container">
              <div class="popup-display">
                <p>Jam Operasional</p>
                <p id="popupJamOperasional">09.00 - 17.00</p>
              </div>
              <a id="popupMapLink" class="popup-pinpoint" href="#" target="_blank">
                <img src="{{ asset('img/tabler-icon-map-pin.png') }}" alt="Map Pin">
              </a>
            </div>
          </div>
        </div>
      </div>

      <p><strong>Lokasi</strong></p>
      <p id="popupAlamat">Jalan Lorem Ipsum, Jakarta Selatan</p>

      <div class="popup-button">
        <button class="profil-salon" id="popupProfileButton">Lihat Profil</button>
        <button class="link-reservasi" id="popupReservasiLink">Lakukan Pesanan</button>
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('js/salonpopup.js') }}"></script>
@endsection
