document.addEventListener("DOMContentLoaded", function () {
  const flash = document.getElementById("popup-flash");
  if (flash) {
    const success = flash.dataset.success;
    const error = flash.dataset.error;

    if (success && success !== '0') {
      Swal.fire({
        icon: 'success',
        title: 'Reservasi Berhasil!',
        text: 'Pesanan Anda telah dibuat.',
        confirmButtonColor: '#ff70b4'
      });
    }

    if (error && error.trim() !== '') {
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: error,
        confirmButtonColor: '#ff70b4'
      });
    }
  }
});
