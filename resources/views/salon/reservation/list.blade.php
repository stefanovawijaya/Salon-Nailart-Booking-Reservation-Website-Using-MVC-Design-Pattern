@extends('layouts.salon')

@section('title', 'Daftar Klien')

@push('styles')
<style>
    #perPage {
        font-size: 20px;
    }
</style>
@endpush

@section('content')
<div class="content">
    <h2 class="fw-bold">Klien</h2>

    <div class="search-container mt-4">
        <input type="text" id="searchInput" onkeyup="searchClients()" placeholder="Cari nama klien...">
        <i class="fa fa-search"></i>
    </div>

    <div class="container-update">
        <div class="container-klien-judul">
            Jangkauan Waktu :
            <input type="date" id="startDate">
            <span>to</span>
            <input type="date" id="endDate">
        </div>

        <div class="container-update1">
            <div class="container-update-table">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Tanggal Reservasi</th>
                        </tr>
                    </thead>
                    <tbody id="tabel-reservasi-klien">
                        {{-- Data akan diisi oleh JavaScript --}}
                    </tbody>
                </table>
            </div>

            <div class="footer-form-daftar-klien">
                <button onclick="goToPrevious()"><u>Halaman Sebelumnya</u></button>
                <select id="perPage" style="font-size: 20px;"  onchange="changePerPage()">
                    <option>10</option>
                    <option>20</option>
                    <option>30</option>
                    <option>40</option>
                    <option>50</option>
                </select>
                <button onclick="goToNext()"><u>Halaman Selanjutnya</u></button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/listclient.js') }}"></script>
@endpush
