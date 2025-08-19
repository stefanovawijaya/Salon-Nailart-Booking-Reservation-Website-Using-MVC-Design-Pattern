@extends('layouts.app')

@section('title', 'Galeri Desain')

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<script>const BASEURL = "{{ url('/') }}";</script>

<style>
  .container-box img {
      border-bottom: 1px solid rgba(255, 112, 180, 1);
      width: 100%;
      height: 130px;
      display: block;
      border-radius: 5px 5px 0 0;
  }

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

  .popup-nama-salon {
      margin-left: 8px !important; 
      margin-top: 4px !important;
      font-weight: bold !important; 
      font-size: 20px !important; 
      display: inline-block !important;
  }

  #popupNamaGaleri{
    margin-top: 4px !important;
  }
</style>

<div class="isi">
  <div class="background-detail">
    <div class="search-container" style="width: 85%; margin: 0 auto;">
      <input type="text" id="searchInput" onkeyup="searchGalleryDesign()" placeholder="Cari nama desain...">
      <i class="fa fa-search"></i>
    </div>

    <div class="container-box1">
      @foreach ($gallery as $item)
        <div class="container-box">
          <img src="{{ asset('img/gallery/' . $item->nailart_image) }}" alt="{{ $item->nailart_name }}">
          <p class="nama-gallery">{{ $item->nailart_name }}</p>
          <button 
            class="lihat-detail-btn"
            data-nama-galeri="{{ $item->nailart_name }}"
            data-deskripsi="{{ $item->nailart_desc }}"
            data-nama-salon="{{ $item->salon->salon_name }}"
            data-gambar-salon="{{ asset('img/salon/' . $item->salon->salon_image) }}"
            data-pinpoint="{{ $item->salon->salon_pinpoint }}"
            data-gambar-galeri="{{ asset('img/gallery/' . $item->nailart_image) }}"
            data-salon-id="{{ $item->salon_id }}"
          >Lihat Detail Salon</button>
        </div>
      @endforeach
    </div>

    <p id="tidakDitemukan">Tidak ditemukan hasil pencarian</p>

    <div id="popupOverlay"></div>

    <div id="popupSalon">
      <div class="popup-header">
        <h3>Detail Galeri</h3>
        <button onclick="tutupPopup()"><img src="{{ asset('img/tombolClose.png') }}"></button>
      </div>
      <hr style="border: 1px solid rgba(255, 112, 180, 1); opacity: 1;">
      <div class="popup-container">
        <img id="popupGambar" src="{{ asset('img/gambarNailArt.jpeg') }}">
        <div>
          <div class="popup-display-flex">
            <img id="popupGambarSalon" src="{{ asset('img/beauty salon square.png') }}">
            <p id="popupNamaSalon" class="popup-nama-salon">Beauty Salon</p>
          </div>
          <div class="pop-up-container">
            <div class="popup-display">
              <p>Nama Galeri</p>
              <p id="popupNamaGaleri">Nail Art Design</p>
            </div>
            <a href="#" id="popupMapLink" class="popup-pinpoint" target="_blank">
              <img src="{{ asset('img/tabler-icon-map-pin.png') }}" style="width: 30px;">
            </a>
          </div>
        </div>
      </div>

      <p><strong>Deskripsi Galeri</strong></p>
      <p id="popupDeskripsi">By Alicia (Senior) - Rp 350.000</p>

      <div class="popup-button">
        <button class="profil-salon" id="popupProfileButton">Lihat Profil</button>
        <button class="link-reservasi" id="popupReservationLink">Lakukan Pesanan</button>
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('js/gallerypopup.js') }}"></script>
<script src="{{ asset('js/gallerysearch.js') }}"></script>
@endsection
