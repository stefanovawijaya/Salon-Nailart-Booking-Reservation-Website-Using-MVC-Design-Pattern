let currentSalonId = null;

function searchSalons() {
  const input = document.getElementById("searchInput").value.toLowerCase();
  const boxes = document.querySelectorAll(".container-box");
  let found = false;

  boxes.forEach(box => {
    const name = box.querySelector(".nama-salon").innerText.toLowerCase();
    if (name.includes(input)) {
      box.style.display = "block";
      found = true;
    } else {
      box.style.display = "none";
    }
  });

  document.getElementById("tidakDitemukan").style.display = found ? "none" : "block";
}

function tutupPopup() {
  document.getElementById("popupOverlay").style.display = "none";
  document.getElementById("popupSalon").style.display = "none";
  currentSalonId = null;
}

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.lihat-detail-btn').forEach(button => {
    button.addEventListener('click', function () {
      const nama = this.dataset.nama;
      const gambar = this.dataset.gambar;
      const jam = this.dataset.jam;
      const alamat = this.dataset.alamat;
      const pinpoint = this.dataset.pinpoint;
      const salonId = this.dataset.salonid;

      currentSalonId = salonId;

      document.getElementById("popupNamaSalon").innerText = nama;
      document.getElementById("popupGambar").src = gambar;
      document.getElementById("popupJamOperasional").innerText = jam;
      document.getElementById("popupAlamat").innerText = alamat;
      document.getElementById("popupMapLink").href = pinpoint;

      document.getElementById("popupOverlay").style.display = "block";
      document.getElementById("popupSalon").style.display = "block";
    });
  });

  document.querySelector(".profil-salon").addEventListener("click", function () {
    if (currentSalonId) {
      const url = `${BASEURL}/salon/salon_detail/${currentSalonId}`;
      window.location.href = url;
    }
  });

  document.getElementById("popupReservasiLink").addEventListener("click", function () {
    if (currentSalonId) {
      const url = `${BASEURL}/reservation/create/${currentSalonId}`;
      window.location.href = url;
    }
  });
});
