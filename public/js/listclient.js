const BASEURL = window.location.origin;
let reservations = [];
let filteredData = [];
let currentPage = 1;
let perPage = 10;
let totalPages = 1;

async function fetchReservations() {
  try {
    const response = await fetch(`${BASEURL}/salon/getSalonReservations`);
    const data = await response.json();

    reservations = data.map(r => {
      const fullDateTimeStr = `${r.reservation_date}T${r.reservation_start}`;
      const fullDateTime = new Date(fullDateTimeStr);

      return {
        id: r.id,
        name: r.client_name,
        status: r.reservation_status,
        total: `Rp ${parseInt(r.reservation_total_price).toLocaleString('id-ID')},00`,
        registered: new Date(r.reservation_created_at).toLocaleDateString('id-ID'),
        treatmentDate: new Date(r.reservation_date).toLocaleDateString('id-ID'),
        fullDate: fullDateTime
      };
    });

    filteredData = [...reservations];
    renderTable();
  } catch (error) {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        icon: 'error',
        title: 'Gagal Memuat Data',
        text: 'Tidak dapat mengambil data reservasi dari server.',
        confirmButtonColor: '#dc3545'
      });
    } else {
      alert("Gagal mengambil data reservasi.");
    }
  }
}


function renderTable() {
  filteredData.sort((a, b) => b.fullDate - a.fullDate);

  totalPages = Math.ceil(filteredData.length / perPage);
  if (currentPage > totalPages) currentPage = totalPages || 1;

  const startIdx = (currentPage - 1) * perPage;
  const pageData = filteredData.slice(startIdx, startIdx + perPage);

  const tbody = document.getElementById("tabel-reservasi-klien");
  tbody.innerHTML = "";

  if (pageData.length === 0) {
    tbody.innerHTML = `<tr><td colspan="4" style="text-align: center; padding: 20px; color: black;">Tidak ada daftar klien yang tersedia</td></tr>`;
    return;
  }

  pageData.forEach(r => {
    const badgeClass = r.status.toLowerCase().replace(/\s/g, '');
    tbody.innerHTML += `
      <tr onclick='openDetail(${r.id})'>
        <td>${r.name}</td>
        <td><span class="badge ${badgeClass}">${r.status}</span></td>
        <td>${r.total}</td>
        <td>
          Jadwal Layanan: ${r.treatmentDate}<br>
          Didaftarkan: ${r.registered}
        </td>
      </tr>`;
  });
}

function searchClients() {
  const keyword = document.getElementById("searchInput").value.toLowerCase();
  const start = document.getElementById("startDate").value;
  const end = document.getElementById("endDate").value;

  let startDate = start ? new Date(start) : null;
  const endDate = end ? new Date(end) : null;

  filteredData = reservations.filter(r => {
    const matchName = r.name.toLowerCase().includes(keyword);
    const regDate = new Date(r.treatmentDate.split('/').reverse().join('-'));
    let startDate = start ? new Date(start) : null;
    const matchDate = (!startDate || regDate >= startDate.setDate(startDate.getDate() - 1)) && (!endDate || regDate <= endDate);

    return matchName && matchDate;
  });

  currentPage = 1;
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

document.getElementById("startDate").addEventListener("change", searchClients);
document.getElementById("endDate").addEventListener("change", searchClients);
document.getElementById("searchInput").addEventListener("keyup", searchClients);

fetchReservations();
