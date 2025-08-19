@extends('layouts.salon')

@section('title', 'Jadwal Salon')

@section('content')
<div class="content">
    <h2 class="fw-bold">Jadwal</h2>

    <div class="calendar-container">
        <div class="month-nav">
            <button onclick="changeMonth(-1)">&lt;</button>
            <p id="monthYear" style="margin-top: 20px; font-size: 20px; font-weight: 600; line-height: 0.1;"></p>
            <button onclick="changeMonth(1)">&gt;</button>
        </div>
        <hr style="border: 1px solid rgba(255, 112, 180, 1); width: 100%; opacity: 1;">
        <div class="kalender-hari" style="padding-top: 12px;">
            <p>S</p><p>S</p><p>R</p><p>K</p><p>J</p><p>S</p><p>M</p>
        </div>
        <hr style="border: 1px solid rgba(255, 112, 180, 1); width: 100%; opacity: 1;">
        <div class="calendar" id="calendar" data-buka-dates='@json($bukaDates)'></div>
    </div>

    <div id="slotWarning" style="display: none; color: red; margin-bottom: 10px;">
        Minimal satu slot buka jangkauan jam harus diisi.
    </div>

    <div class="form-informasi">
        <div class="form-informasi-postingan">Informasi Jadwal</div>
        <div class="form-informasi-isi">
            <form action="{{ url('/schedule/save') }}" method="POST">
                @csrf
                <label for="jadwal" style="font-size: 20px;">Jadwal Tersedia</label>
                <input type="text" id="jadwal" name="schedule_date" placeholder="DD-MM-YYYY" required>

                <label style="display: block; margin-bottom: 8px; font-size: 20px">Status ketersediaan salon hari ini</label>
                <div class="status-toggle">
                    <div class="toggle-option buka active" onclick="toggleStatus(this)">Buka</div>
                    <div class="toggle-option tutup" onclick="toggleStatus(this)">Tutup</div>
                </div>
                <input type="hidden" name="salon_status" id="salonStatus" value="Buka">

                <div class="jam-mulai-selesai" style="width: 100%;">
                    <label style="font-size: 20px; display: block;">Jam Operasional Salon</label>
                    <div style="display: flex; width: 100%;">
                        <input type="time" name="open_time" style="width: 48%;" required>
                        <p style="margin-top: 12px; margin-right: 8px; margin-left: 4px;">-</p>
                        <input type="time" name="close_time" style="width: 48%;" required>
                    </div>
                </div>

                <div class="slot-container" id="slotContainer">
                    <div class="slot-group">
                        <div class="jam-mulai-selesai">
                            <label style="font-size: 20px; display: block;">Slot Buka Jangkauan Jam</label>
                            <input type="time" name="slot_start[]" required>
                            <p style="display: inline-block;">-</p>
                            <input type="time" name="slot_end[]" required>
                        </div>
                    </div>
                </div>

                <button id="addSlot" type="button" style="background: none; border: none; display:block;">
                    <img src="{{ asset('img/buttonPlusSmall.png') }}" alt="Add Slot">
                </button>

                <div style="margin-top: 20px; display: flex; gap: 10px;">
                <input type="submit" value="Simpan" style="
                    width: 244px;
                    background-color: rgba(174, 101, 212, 1); 
                    color: white; 
                    padding: 12px 36px; 
                    border: none; 
                    border-radius: 6px; 
                    font-size: 16px; 
                    font-weight: bold; 
                    cursor: pointer;
                    transition: background-color 0.2s ease;
                ">
                
                <!-- <button type="button" id="updateBtn" style="
                    width: 244px;
                    background-color: #28a745; 
                    color: white; 
                    padding: 12px 36px; 
                    border: none; 
                    border-radius: 6px; 
                    font-size: 16px; 
                    font-weight: bold; 
                    cursor: pointer;
                    transition: background-color 0.2s ease;
                    display: none;
                ">Perbarui</button>
            </div> -->

            <meta name="csrf-token" content="{{ csrf_token() }}">
            </form>
        </div>
    </div>
</div>

@include('salon.schedule.partials.styles')
@include('salon.schedule.partials.scripts')
@endsection
