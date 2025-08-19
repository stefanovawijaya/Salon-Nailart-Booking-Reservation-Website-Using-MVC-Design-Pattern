function statusToClass(status) {
  return status.replace(/\s/g, '');
}

function applyStatusClass(selectElement, statusValue) {
  selectElement.className = 'status-select ' + statusToClass(statusValue);
}

let statusSementara = null;
const selectStatus = document.querySelector(".status-select");

window.onload = function () {
  const statusFromServer = document.getElementById("reservationStatus").value;
  selectStatus.value = statusToClass(statusFromServer);
  applyStatusClass(selectStatus, statusFromServer);
};

selectStatus.addEventListener('change', function () {
  statusSementara = this.value;
  document.getElementById("popupKonfirmasi").style.display = "flex";
});

function konfirmasiStatus(ya) {
  document.getElementById("popupKonfirmasi").style.display = "none";

  if (ya && statusSementara) {
    const displayMap = {
      Selesai: "Selesai",
      SedangBerlangsung: "Sedang Berlangsung",
      Dibatalkan: "Dibatalkan"
    };

    const newStatus = displayMap[statusSementara] || statusSementara;

    applyStatusClass(selectStatus, newStatus);
    simpanPerubahanStatus(newStatus);
  } else {
    const revertValue = document.getElementById("reservationStatus").value;
    selectStatus.value = statusToClass(revertValue);
    applyStatusClass(selectStatus, revertValue);
  }

  statusSementara = null;
}

function simpanPerubahanStatus(status) {
  const reservationId = document.getElementById("reservationId").value;

  fetch("/reservation/update-status", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
    },
    body: JSON.stringify({
      id: reservationId,
      status: status
    })
  })
  .then(response => response.json())
  .then(result => {
    if (result.success) {
      document.getElementById("reservationStatus").value = status;
      sessionStorage.setItem('statusUpdated', 'true');
      window.location.href = document.referrer;
    } else {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'error',
          title: 'Gagal Mengubah Status',
          text: result.error || 'Terjadi kesalahan.',
          confirmButtonColor: '#dc3545'
        });
      } else {
        alert("Gagal mengubah status: " + (result.error || "Terjadi kesalahan."));
      }
    }
  })
  .catch(error => {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        icon: 'error',
        title: 'Kesalahan Jaringan',
        text: 'Terjadi kesalahan saat menghubungi server.',
        confirmButtonColor: '#dc3545'
      });
    } else {
      alert("Kesalahan jaringan saat menyimpan status.");
    }
  });
}
