<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $salon->salon_name }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <link href="{{ url('/css/style.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="isi">
  <div class="tombol-kembali">
    <a onclick="history.back()">&lt;&emsp;Kembali</a>
  </div>

  <div class="logo-section">
    <img src="{{ url('/img/salon/' . $salon->salon_image) }}" alt="Logo" />
    <h1>{{ $salon->salon_name }}</h1>
    <p>{{ $salon->salon_description ?? 'Cantik itu mudah. Mulai dari ujung jari Anda.' }}</p>
  </div>

  <div class="info-card">
    <div class="info-row">
      <img src="{{ url('/img/tabler-icon-calendar-clock.png') }}" />
      <div>{{ $salon->salon_operational_hour }}</div>
    </div>
    <div class="info-row">
      <img src="{{ url('/img/tabler-icon-map-pin.png') }}" />
      <div>{{ $salon->salon_location }}</div>
    </div>
    <div class="info-row">
      <img src="{{ url('/img/tabler-icon-device-mobile.png') }}" />
      <div>{{ $salon->salon_phonenumber }}</div>
    </div>
    <div class="info-row">
      <img src="{{ url('/img/tabler-icon-mail.png') }}" />
      <div>{{ $salon->salon_email }}</div>
    </div>
  </div>

  <div class="action-buttons">
    <button onclick="window.open('{{ $salon->salon_pinpoint }}', '_blank')" class="btn location">Cek Lokasi</button>
    <button onclick="window.location.href='{{ url('/reservation/create/' . $salon->salon_id) }}'" class="btn order">Lakukan Pesanan</button>
  </div>

  <div class="tabs">
    <div class="tab active">Galeri Desain</div>
    <div class="tab">Daftar Layanan</div>
    <div class="tab">Voucher Diskon</div>
  </div>

  {{-- Gallery --}}
  <div class="gallery">
    @foreach ($gallery as $item)
      @php
        $image = addslashes(url('/img/gallery/' . $item->nailart_image));
        $name = addslashes($item->nailart_name);
        $desc = addslashes($item->nailart_desc);
        $date = date('d/m/Y | H:i', strtotime($item->created_at));
      @endphp
      <img
        src="{{ $image }}"
        alt="{{ $item->nailart_name }}"
        onclick="showImageDetail('{{ $image }}', '{{ $name }}', '{{ $desc }}', '{{ $date }}')"
      />
    @endforeach
  </div>

  {{-- Treatments --}}
  <div class="gallery gallery-layanan" style="display: none;">
    @foreach ($treatments as $treatment)
      @php
        $img = addslashes(url('/img/treatment/' . $treatment->treatment_image));
        $name = addslashes($treatment->treatment_name);
        $price = number_format($treatment->treatment_price, 0, ',', '.');
        $date = date('d/m/Y | H:i', strtotime($treatment->created_at));
      @endphp
      <img
        src="{{ $img }}"
        alt="{{ $treatment->treatment_name }}"
        title="{{ $treatment->treatment_name }}"
        onclick="showImageTreatmentDetail('{{ $img }}', '{{ $name }}', '{{ $price }}', '{{ $date }}')"
      />
    @endforeach
  </div>

  {{-- Vouchers --}}
  <div class="gallery voucher-diskon" style="display: none;">
    @foreach ($vouchers as $voucher)
      @php
        $img = addslashes(url('/img/voucher/' . $voucher->voucher_image));
        $code = addslashes($voucher->voucher_code);
        $value = number_format($voucher->voucher_value, 0, ',', '.');
        $date = date('d/m/Y | H:i', strtotime($voucher->created_at));
      @endphp
      <img
        src="{{ $img }}"
        alt="Voucher {{ $voucher->voucher_code }}"
        title="{{ $voucher->voucher_code }}"
        onclick="showImageVoucherDetail('{{ $img }}', '{{ $code }}', '{{ $value }}', '{{ $date }}')"
      />
    @endforeach
  </div>

  {{-- MODALS --}}
  <div id="imageModal" class="modal-container" style="display: none;">
    <div style="width: 430px; background: white; border-radius: 30px;">
      <img id="modalImage" src="" alt="Preview" style="width:100%; height:430px; border-radius: 30px 30px 0px 0px;">
      <div style="padding: 12px 24px;">
        <h4 id="modalTitle" style="margin: 6px 0 4px; font-size: 16px; color: black;">Gallery Name</h4>
        <p id="modalDesc" style="margin: 0 0 4px; font-size: 14px; color: black;">Gallery Description</p>
        <p id="modalDate" style="margin: 0; font-size: 12px; color: rgba(255, 112, 180, 1);">Date</p>
      </div>
    </div>
    <button onclick="closeModal()" style="margin-top: 20px; background-color: transparent; border: none;">
      <img src="{{ url('/img/tombolClose.png') }}" style="width: 40px;">
    </button>
  </div>

  <div id="treatmentModal" class="modal-container" style="display: none;">
    <div style="width: 430px; background: white; border-radius: 30px;">
      <img id="modalImageTreatment" src="" alt="Preview" style="width:100%; height:430px; border-radius: 30px 30px 0px 0px;">
      <div style="padding: 12px 24px;">
        <h4 id="modalTreatmentName" style="margin: 6px 0 4px; font-size: 16px; color: black;">Treatment Name</h4>
        <p id="modalPrice" style="margin: 0 0 4px; font-size: 14px; color: black;">Treatment Price</p>
        <p id="modalTanggal" style="margin: 0; font-size: 12px; color: rgba(255, 112, 180, 1);">Date</p>
      </div>
    </div>
    <button onclick="closeModalTreatment()" style="margin-top: 20px; background-color: transparent; border: none;">
      <img src="{{ url('/img/tombolClose.png') }}" style="width: 40px;">
    </button>
  </div>

  <div id="voucherModal" class="modal-container" style="display: none;">
    <div style="width: 430px; background: white; border-radius: 30px;">
      <img id="modalImageVoucher" src="" alt="Preview" style="width:100%; height:430px; border-radius: 30px 30px 0px 0px;">
      <div style="padding: 12px 24px;">
        <h4 id="modalVoucherCode" style="margin: 6px 0 4px; font-size: 16px; color: black;">Voucher Code</h4>
        <p id="modalValue" style="margin: 0 0 4px; font-size: 14px; color: black;">Voucher Value</p>
        <p id="modalTanggall" style="margin: 0; font-size: 12px; color: rgba(255, 112, 180, 1);">Date</p>
      </div>
    </div>
    <button onclick="closeModalVoucher()" style="margin-top: 20px; background-color: transparent; border: none;">
      <img src="{{ url('/img/tombolClose.png') }}" style="width: 40px;">
    </button>
  </div>

</div>

<script src="{{ url('/js/detailsalon.js') }}"></script>

</body>
</html>
