document.querySelectorAll(".lihat-detail-btn").forEach(button => {
  button.addEventListener("click", () => {
    const namaGaleri = button.dataset.namaGaleri;
    const gambarGaleri = button.dataset.gambarGaleri;
    const namaSalon = button.dataset.namaSalon;
    const gambarSalon = button.dataset.gambarSalon;
    const deskripsi = button.dataset.deskripsi;
    const pinpoint = button.dataset.pinpoint;
    const salonId = button.dataset.salonId;

    document.getElementById("popupNamaGaleri").innerText = namaGaleri;
    document.getElementById("popupGambar").src = gambarGaleri;
    document.getElementById("popupNamaSalon").innerText = namaSalon;
    document.getElementById("popupGambarSalon").src = gambarSalon;
    document.getElementById("popupDeskripsi").innerText = deskripsi;
    document.getElementById("popupMapLink").href = pinpoint;

    document.querySelector('.profil-salon').onclick = function () {
      window.location.href = `${BASEURL}/salon/salon_detail/${salonId}`;
    };

    document.getElementById('popupReservationLink').onclick = function () {
      window.location.href = `${BASEURL}/reservation/create/${salonId}`;
    };

    document.getElementById("popupOverlay").style.display = "block";
    document.getElementById("popupSalon").style.display = "block";
  });
});

function redirectToReservation(button) {
    const salonId = button.getAttribute('data-salon-id');
    window.location.href = BASEURL + '/reservation/index/' + salonId;
}


function tutupPopup() {
  document.getElementById('popupSalon').style.display = 'none';
  document.getElementById('popupOverlay').style.display = 'none';
}
