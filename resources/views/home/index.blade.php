@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="container py-4 bg-white">
    
    <div id="homeCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner rounded-4">
            <div class="carousel-item active">
                <img src="{{ asset('img/carousel-1.png') }}" class="d-block w-100" alt="Banner" />
            </div>
            <div class="carousel-item">
                <img src="{{ asset('img/carousel-1.png') }}" class="d-block w-100" alt="Banner" />
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-light rounded-circle p-2" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-light rounded-circle p-2" aria-hidden="true"></span>
        </button>
    </div>

    <div class="p-3 mb-4" style="background-color: #FFE5F2; border: 2px solid #FF70B4; border-radius: 15px;">
        <h5 class="fw-bold mb-3" style="color: #FF70B4;">Pilihan Terbaik</h5>
        <hr class="mb-3 mt-0" style="border: 2px solid #FF70B4; opacity: 1;">

        <div class="d-flex align-items-center">
            <div class="d-flex gap-3 overflow-auto pb-2 flex-grow-1" style="overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none;">
                
                @foreach($gallery as $item)
                    <div class="card shadow-sm" style="min-width: 130px; max-width: 130px; max-height: 200px; border-radius: 5px; overflow: hidden; border: 2px solid #FF70B4;">
                        <img src="{{ asset('img/gallery/' . $item->nailart_image) }}"
                            class="w-100"
                            alt="{{ $item->nailart_image }}"
                            style="height: 130px; object-fit: cover; display: block; border-bottom: 2px solid #FF70B4;">
                        <div class="card-body text-center p-2">
                            <p class="small fw-semibold mb-2" style="font-size: 12px;">{{ $item->nailart_name }}</p>
                            <a href="{{ route('reservation.create', $item->salon_id) }}" class="btn btn-sm text-white w-100 px-2 py-1"
                                style="font-size: 11px; background-color: #AE65D4; border-radius: 15px;">
                                Reservasi Segera
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>
            
            <div class="ms-3 d-flex align-items-center">
                <i class="fa-solid fa-chevron-right" style="font-size: 50px; color: #FF70B4;"></i>
            </div>
        </div>
    </div>

    <div class="p-3 mb-4" style="background-color: #FFE5F2; border: 2px solid #FF70B4; border-radius: 15px;">
        <h5 class="fw-bold mb-3" style="color: #FF70B4;">Voucher Deals</h5>
        <hr class="mb-3 mt-0" style="border: 2px solid #FF70B4; opacity: 1;">

        <div class="d-flex align-items-center">
            <div class="d-flex gap-3 overflow-auto pb-2 flex-grow-1" style="overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none;">
                
                @foreach($voucher as $voucher)
                <div class="card shadow-sm" style="min-width: 130px; max-width: 130px; max-height: 200px; border-radius: 5px; overflow: hidden; border: 2px solid #FF70B4;">
                    <img src="{{ asset('img/voucher/' . $voucher->voucher_image) }}"
                        class="w-100"
                        alt="{{ $voucher->voucher_image }}"
                        style="height: 130px; object-fit: cover; display: block; border-bottom: 2px solid #FF70B4;">
                    <div class="card-body text-center p-2">
                        <p class="small fw-semibold mb-2" style="font-size: 12px;">{{ $voucher->voucher_code }}</p>
                        <a href="{{ route('reservation.create', ['salon' => $voucher->salon_id, 'voucher_code' => $voucher->voucher_code]) }}" class="btn btn-sm text-white w-100 px-2 py-1"
                            style="font-size: 11px; background-color: #AE65D4; border-radius: 15px;">
                            Reservasi Segera
                        </a>
                    </div>
                </div>
                @endforeach

            </div>
            
            <div class="ms-3 d-flex align-items-center">
                <i class="fa-solid fa-chevron-right" style="font-size: 50px; color: #FF70B4;"></i>
            </div>
        </div>
    </div>
</div>
@endsection