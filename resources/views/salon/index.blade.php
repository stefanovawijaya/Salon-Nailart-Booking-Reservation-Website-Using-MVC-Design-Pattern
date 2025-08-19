@extends('layouts.salon')

@section('title', 'Beranda Salon')

@section('content')
<style>
    #perPage {
        font-size: 20px;
    }

    .status-card {
        border-radius: 12px;
        padding: 20px 30px;
        width: 25%;
        height: 180px;
        cursor: pointer;
        color: white;
        text-align: left;
        box-shadow: 0 3px 3px rgba(119, 119, 119, 0.6);
    }

    .img-status {
        vertical-align: sub;
        width: 50px;
        display: inline-block;
    }

    .status-card.aktif {
        height: 220px;
        padding-top: 50px;
        box-shadow: 0 3px 3px rgba(119, 119, 119, 0.6);
        transition: height 0.3s;
    }

    #countSemua, #countBerlangsung, #countSelesai, #countDibatalkan {
        margin-top: 8px;
    }
</style>

<div class="content">
    <form id="csrf-form" style="display: none;">
        @csrf
    </form>
    
    <h2 class="fw-bold">Beranda</h2>
    <div class="status-cards">
        <div class="status-card semua aktif" onclick="filterStatus('Semua')">
            <img src="{{ asset('img/Semua.png') }}" class="img-status"> Semua <span id="countSemua">0</span>
        </div>
        <div class="status-card berlangsung" onclick="filterStatus('Sedang Berlangsung')">
            <img src="{{ asset('img/Sedang Berlangsung.png') }}" class="img-status"> Sedang Berlangsung <span id="countBerlangsung">0</span>
        </div>
        <div class="status-card selesai" onclick="filterStatus('Selesai')">
            <img src="{{ asset('img/Selesai.png') }}" class="img-status"> Selesai <span id="countSelesai">0</span>
        </div>
        <div class="status-card dibatalkan" onclick="filterStatus('Dibatalkan')">
            <img src="{{ asset('img/Dibatalkan.png') }}" class="img-status"> Dibatalkan <span id="countDibatalkan">0</span>
        </div>
    </div>
    
    <h3 class="fw-bold">Reservasi</h3>
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
                    <tbody id="tabel-reservasi-klien"></tbody>
                </table>
            </div>
            <div class="footer-form-daftar-klien">
                <button onclick="goToPrevious()"><u>Halaman Sebelumnya</u></button>
                <select style="font-size: 20px;" id="perPage" onchange="changePerPage()">
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

<script>
const BASEURL = "{{ url('/') }}";
let reservations = [];
let currentPage = 1;
let perPage = 10;
let currentStatus = "Semua";
let totalPages = 1;

async function fetchReservations() {
    try {        
        let csrfToken = '';
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            csrfToken = csrfMeta.getAttribute('content');
        } else {
            const csrfForm = document.querySelector('#csrf-form input[name="_token"]');
            if (csrfForm) {
                csrfToken = csrfForm.value;
            }
        }
                
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        };
        
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken;
        }
        
        const response = await fetch(`${BASEURL}/salon/getSalonReservations`, {
            method: 'GET',
            headers: headers
        });
                
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();

        if (data.error) {
            throw new Error(data.error);
        }

        reservations = data.map(r => ({
            id: r.id,
            name: r.client_name || 'Unknown',
            status: r.reservation_status || 'Unknown',
            total: `Rp ${parseInt(r.reservation_total_price || 0).toLocaleString('id-ID')}`,
            treatmentDate: new Date(r.reservation_date).toLocaleDateString('id-ID'),
            registered: new Date(r.reservation_created_at).toLocaleDateString('id-ID')
        }));

        updateCounts();
        renderTable();
    } catch (error) {
        console.error("Failed to fetch reservations", error);
        const tbody = document.getElementById("tabel-reservasi-klien");
        tbody.innerHTML = `<tr><td colspan="4" style="text-align: center; padding: 20px; color: red;">Error loading reservations: ${error.message}</td></tr>`;
    }
}

function updateCounts() {
    document.getElementById('countSemua').textContent = reservations.length;
    document.getElementById('countBerlangsung').textContent = reservations.filter(r => r.status === 'Sedang Berlangsung').length;
    document.getElementById('countSelesai').textContent = reservations.filter(r => r.status === 'Selesai').length;
    document.getElementById('countDibatalkan').textContent = reservations.filter(r => r.status === 'Dibatalkan').length;
}

function renderTable() {
    const start = document.getElementById("startDate").value;
    const end = document.getElementById("endDate").value;
    let startDate = start ? new Date(start) : null;
    const endDate = end ? new Date(end) : null;

    let filtered = reservations.filter(r => {
        const matchStatus = currentStatus === "Semua" || r.status === currentStatus;
        const regDate = new Date(r.treatmentDate.split('/').reverse().join('-'));
        let startDate = start ? new Date(start) : null;
        const matchDate = (!startDate || regDate >= startDate.setDate(startDate.getDate() - 1)) && (!endDate || regDate <= endDate);
        return matchStatus && matchDate;
    });

    filtered.sort((a, b) => {
        const statusPriority = {
            "Sedang Berlangsung": 0,
            "Pending": 1,
            "Confirmed": 2,
            "Selesai": 3,
            "Dibatalkan": 4,
            "Unknown": 5
        };

        const statusA = statusPriority[a.status] ?? 99;
        const statusB = statusPriority[b.status] ?? 99;

        if (statusA !== statusB) return statusA - statusB;

        const dateA = new Date(a.treatmentDate.split('/').reverse().join('-'));
        const dateB = new Date(b.treatmentDate.split('/').reverse().join('-'));
        return dateA - dateB;
    });

    totalPages = Math.ceil(filtered.length / perPage);
    if (currentPage > totalPages) currentPage = totalPages || 1;

    const startIdx = (currentPage - 1) * perPage;
    const pageData = filtered.slice(startIdx, startIdx + perPage);

    const tbody = document.getElementById("tabel-reservasi-klien");
    tbody.innerHTML = "";

    if (pageData.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4" style="text-align: center; padding: 20px; color: black;">Tidak ada daftar klien yang tersedia</td></tr>`;
        return;
    }

    pageData.forEach(r => {
        const statusText = r.status || 'Unknown';
        const badgeClass = statusText.toLowerCase().replace(/\s/g, '');
        tbody.innerHTML += `
            <tr onclick='openDetail(${r.id})'>
                <td>${r.name}</td>
                <td><span class="badge ${badgeClass}">${statusText}</span></td>
                <td>${r.total}</td>
                <td>
                    Jadwal Layanan: ${r.treatmentDate}<br>
                    Didaftarkan: ${r.registered}
                </td>
            </tr>`;
    });
}

function filterStatus(status) {
    currentStatus = status;
    currentPage = 1;

    document.querySelectorAll(".status-card").forEach(card => card.classList.remove("aktif"));

    const statusClassMap = {
        "Semua": "semua",
        "Sedang Berlangsung": "berlangsung",
        "Selesai": "selesai",
        "Dibatalkan": "dibatalkan"
    };

    const activeCard = document.querySelector(`.status-card.${statusClassMap[status]}`);
    if (activeCard) {
        activeCard.classList.add("aktif");
    }

    renderTable();
}

function goToNext() {
    if (currentPage < totalPages) {
        currentPage++;
        renderTable();
    }
}

function goToPrevious() {
    if (currentPage > 1) {
        currentPage--;
        renderTable();
    }
}

function changePerPage() {
    perPage = parseInt(document.getElementById("perPage").value);
    currentPage = 1;
    renderTable();
}

function openDetail(reservationId) {
    window.location.href = `${BASEURL}/salon/reservation/${reservationId}/detail`;
}

document.getElementById("startDate").addEventListener("change", () => {
    currentPage = 1;
    renderTable();
});

document.getElementById("endDate").addEventListener("change", () => {
    currentPage = 1;
    renderTable();
});

document.addEventListener("DOMContentLoaded", () => {
  if (sessionStorage.getItem('statusUpdated') === 'true') {
    sessionStorage.removeItem('statusUpdated');
    fetchReservations();
  } else {
    fetchReservations();
  }
});
</script>
@endsection