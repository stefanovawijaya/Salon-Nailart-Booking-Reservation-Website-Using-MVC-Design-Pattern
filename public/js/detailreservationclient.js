window.addEventListener("DOMContentLoaded", function () {
    const statusBadge = document.getElementById('statusBadge');
    
    function applyStatusColor() {
        const status = statusBadge.textContent.trim();
        let statusColor = '#ccc';

        switch (status) {
            case 'Sedang Berlangsung':
                statusColor = '#E7903F';
                break;
            case 'Selesai':
                statusColor = '#3CCA8E';
                break;
            case 'Dibatalkan':
                statusColor = '#DD6A57';
                break;
            default:
                statusColor = '#ccc';
                break;
        }
        
        statusBadge.style.backgroundColor = statusColor;
    }

    if (statusBadge) {
        applyStatusColor();
    }

    const data = JSON.parse(localStorage.getItem("selectedBooking"));
    if (!data) return;

    document.getElementById("namaPemesan").textContent = "Karina Bella";
    document.getElementById("tanggalPesanan").textContent = new Date(data.date).toLocaleDateString('id-ID', {
        day: '2-digit', month: 'long', year: 'numeric'
    });
    document.getElementById("waktuPesanan").textContent = new Date(data.date).toLocaleTimeString('id-ID', {
        hour: '2-digit', minute: '2-digit'
    });

    document.getElementById("jadwalPesanan").textContent = data.scheduled;
    document.getElementById("waktuDijadwalkan").textContent = data.time;
    document.querySelector(".jumlah-biaya-akhir").textContent = data.total;
   
    const status = data.status;
    statusBadge.textContent = status;
    
    applyStatusColor();
});