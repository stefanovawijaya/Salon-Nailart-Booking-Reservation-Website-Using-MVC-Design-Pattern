@extends('layouts.app')

@section('title', 'Riwayat Reservasi')

@push('styles')
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<style>
.filters {
    display: flex;
    gap: 10px;
    padding: 16px 0;
    overflow-x: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.filters::-webkit-scrollbar {
    display: none;
}
.status-badge {
    display: flex;
    padding: 4px 10px;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    color: white;
    margin-top: 8px;
    margin-right: auto;
}
</style>
@endpush

@section('content')
<div class="isi">
    <div class="background-detail">
        <div class="filters">
            <button class="filter-btn aktip" data-status="semua" onclick="filterStatus('semua')">SEMUA</button>
            <button class="filter-btn" data-status="sedangberlangsung" onclick="filterStatus('sedangberlangsung')">SEDANG BERLANGSUNG</button>
            <button class="filter-btn" data-status="selesai" onclick="filterStatus('selesai')">SELESAI</button>
            <button class="filter-btn" data-status="dibatalkan" onclick="filterStatus('dibatalkan')">DIBATALKAN</button>
        </div>
        <div id="bookingList"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const BASEURL = "{{ url('/') }}";
let bookings = [];

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit', month: 'short', year: 'numeric'
    });
}

function groupByDate(arr) {
    return arr.reduce((acc, item) => {
        const key = formatDate(item.reservation_created_at);
        acc[key] = acc[key] || [];
        acc[key].push(item);
        return acc;
    }, {});
}

function formatDateTime(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit', month: 'short', year: 'numeric'
    }) + ', ' + date.toLocaleTimeString('id-ID', {
        hour: '2-digit', minute: '2-digit', hour12: false
    });
}

function formatTime(timeStr) {
    const [hour, minute] = timeStr.split(':');
    return `${hour}:${minute}`;
}

function renderBookings(filter = "semua") {
    const container = document.getElementById("bookingList");
    container.innerHTML = "";

    let filtered = filter === "semua"
        ? bookings
        : bookings.filter(b =>
            b.reservation_status &&
            b.reservation_status.toLowerCase().replace(/\s+/g, '') === filter.toLowerCase()
        );

    filtered.sort((a, b) => new Date(b.reservation_created_at) - new Date(a.reservation_created_at));
    const grouped = groupByDate(filtered);

    for (const [date, list] of Object.entries(grouped)) {
        container.innerHTML += `
            <div class="date-group"><i class="fa-solid fa-calendar-days"></i> ${date}</div>
        `;

        list.forEach(b => {
            if (!b.reservation_id) return;

            const badgeClass = b.reservation_status.toLowerCase().replace(/\s+/g, '');
            const imgSrc = b.salon_image
                ? `${BASEURL}/img/salon/${b.salon_image}`
                : `${BASEURL}/img/salon/default.png`;

            container.innerHTML += `
                <div class="card" data-id="${b.reservation_id}" style="cursor: pointer;">
                    <div style="display: flex;">
                        <img src="${imgSrc}" style="border-radius: 6px; width: 50px;">
                        <div style="margin-left: 8px;">
                            <div style="font-size:18px;"><strong>${b.salon_name}</strong></div>
                            <div style="font-size:16px;">${formatDateTime(b.reservation_created_at)}</div>
                        </div>
                    </div>
                    <div class="detail-janji-container">
                        <div class="detail-janji-salon-detail" style="margin-top: 8px; font-size:14px; width: 150px">
                            <p><strong>Tanggal Dijadwalkan</strong></p>
                            <p><strong>Waktu Dijadwalkan</strong></p>
                            <p><strong>Total Biaya Akhir</strong></p>
                        </div>
                        <div style="margin-top: 8px; font-size:14px;" class="detail-janji-salon-titik-dua">
                            <p>:</p><p>:</p><p>:</p>
                        </div>
                        <div class="detail-janji-salon-isi" style="margin-top: 8px; font-size:14px;">
                            <p>${formatDate(b.reservation_date)}</p>
                            <p>${extractHourMinute(b.reservation_start)} - ${extractHourMinute(b.reservation_end)}</p>
                            <p>Rp ${parseInt(b.reservation_total_price).toLocaleString('id-ID')}</p>
                        </div>
                    </div>
                    <span class="status-badge ${badgeClass}">${b.reservation_status}</span>
                </div>
            `;
        });
    }
}

function loadBookings(status = "semua") {
    fetch(`${BASEURL}/reservation/history?status=${status}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(json => {
        bookings = Array.isArray(json.data) ? json.data : [];
        renderBookings(status);
    })
    .catch(err => {
        console.error("Failed to load bookings:", err);
    });
}

function filterStatus(status) {
    document.querySelectorAll(".filter-btn").forEach(btn => btn.classList.remove("aktip"));
    const clicked = [...document.querySelectorAll(".filter-btn")].find(btn => btn.dataset.status === status);
    if (clicked) clicked.classList.add("aktip");

    loadBookings(status);
}

function extractHourMinute(isoString) {
    const timePart = isoString.split('T')[1];
    if (!timePart) return '-';
    return timePart.slice(0, 5);
}

loadBookings();

document.getElementById("bookingList").addEventListener("click", function (e) {
    const card = e.target.closest(".card");
    if (card) {
        const id = card.dataset.id;
        if (!id || id === "undefined") return;
        window.location.href = `${BASEURL}/reservation/${id}/detail`;
    }
});
</script>
@endpush
