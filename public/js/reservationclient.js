let currentDateKlien = new Date();
let selectedDateKlien = null;
let selectedSlot = null;
let appliedVoucher = null;

function generateCalendarKlien() {
    const year = currentDateKlien.getFullYear();
    const month = currentDateKlien.getMonth();
    const today = new Date();
    
    const monthNames = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];
    
    const monthYearElement = document.getElementById('monthYearKlien');
    if (monthYearElement) {
        monthYearElement.textContent = `${monthNames[month]} ${year}`;
    }
    
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    let startingDay = firstDay.getDay();
    startingDay = (startingDay === 0) ? 6 : startingDay - 1;
    const daysInMonth = lastDay.getDate();
    
    const calendarContainer = document.getElementById('calendarKlien');
    if (!calendarContainer) {
        console.error('Calendar container not found');
        return;
    }
    
    calendarContainer.innerHTML = '';
    
    for (let i = 0; i < startingDay; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'calendar-day-klien empty';
        calendarContainer.appendChild(emptyCell);
    }
    
    if (typeof BASEURL === 'undefined' || typeof SALON_ID === 'undefined') {
        console.error('BASEURL or SALON_ID is not defined');
        showCalendarError('Configuration error. Please refresh the page.');
        return;
    }
    
    fetch(`${BASEURL}/reservation/getBukaDates/${SALON_ID}/${year}/${month + 1}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(schedules => {
            const availableDates = Array.isArray(schedules) ? schedules.map(schedule => {
                const date = new Date(schedule.schedule_date);
                return date.getDate();
            }) : [];
            
            generateCalendarDays(year, month, daysInMonth, today, availableDates, calendarContainer);
        })
        .catch(error => {
            console.error('Error fetching available dates:', error);
            showCalendarError('Failed to load available dates. Please try again.');
        });
}

function generateCalendarDays(year, month, daysInMonth, today, availableDates, calendarContainer) {
    for (let day = 1; day <= daysInMonth; day++) {
        const dayCell = document.createElement('div');
        dayCell.className = 'calendar-day-klien';
        dayCell.textContent = day;
        
        const currentDate = new Date(year, month, day);
        const isToday = currentDate.toDateString() === today.toDateString();
        const isPastDate = currentDate < today && !isToday;
        const isAvailable = availableDates.includes(day);
        
        if (isPastDate) {
            dayCell.classList.add('past-date');
            dayCell.title = 'Tanggal sudah berlalu';
        } else if (isAvailable) {
            dayCell.classList.add('available');
            dayCell.title = 'Klik untuk memilih tanggal';
            dayCell.addEventListener('click', (e) => {
                e.preventDefault();
                selectDateKlien(year, month, day, dayCell);
            });
        } else {
            dayCell.classList.add('unavailable');
            dayCell.title = 'Salon tutup pada tanggal ini';
        }
        
        if (isToday) {
            dayCell.classList.add('today');
        }
        
        calendarContainer.appendChild(dayCell);
    }
}

function showCalendarError(message) {
    const calendarContainer = document.getElementById('calendarKlien');
    if (calendarContainer) {
        calendarContainer.innerHTML = `<p class="error-message" style="color: red; text-align: center; grid-column: 1/-1;">${message}</p>`;
    }
}

function selectDateKlien(year, month, day, clickedElement) {
    document.querySelectorAll('.calendar-day-klien.selected').forEach(cell => {
        cell.classList.remove('selected');
    });
    
    if (clickedElement) {
        clickedElement.classList.add('selected');
    }
    
    selectedDateKlien = new Date(year, month, day);
    const formattedDate = `${day.toString().padStart(2, '0')}-${(month + 1).toString().padStart(2, '0')}-${year}`;
    
    const selectedDateInput = document.getElementById('selectedDateInput');
    if (selectedDateInput) {
        selectedDateInput.value = formattedDate;
    }
    
    resetSlotSelection();
    
    loadAvailableSlots(year, month, day);
}

function resetSlotSelection() {
    selectedSlot = null;
    
    const selectedStartTime = document.getElementById('selectedStartTime');
    const selectedEndTime = document.getElementById('selectedEndTime');
    
    if (selectedStartTime) selectedStartTime.value = '';
    if (selectedEndTime) selectedEndTime.value = '';
    
    document.querySelectorAll('.slot-button.selected').forEach(btn => {
        btn.classList.remove('selected');
    });
}

function loadAvailableSlots(year, month, day) {
    const dateString = `${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
    const url = `${BASEURL}/reservation/getslots/${dateString}/${SALON_ID}`;
    

    const slotsContainer = document.querySelector('.tombol-slot');
    if (!slotsContainer) {
        console.error('Slots container not found');
        return;
    }
    
    slotsContainer.innerHTML = '<p class="loading-slots">Memuat slot waktu...</p>';

    fetch(url)
        .then(response => {
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            
            slotsContainer.innerHTML = '';
            
            if (data.slots && Array.isArray(data.slots) && data.slots.length > 0) {
                renderSlots(data.slots, slotsContainer);
            } else {
                slotsContainer.innerHTML = '<p class="no-slots">Tidak ada slot tersedia untuk tanggal ini.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching slots:', error);
            slotsContainer.innerHTML = `<p class="error-slots" style="color: red;">Gagal memuat slot waktu. Silakan coba lagi.</p>`;
        });
}

function renderSlots(slots, container) {
    
    if (!slots || !Array.isArray(slots)) {
        container.innerHTML = '<p class="no-slots">Data slot tidak valid.</p>';
        return;
    }
    
    slots.forEach(slot => {
        
        const slotButton = document.createElement('button');
        slotButton.type = 'button';
        
        const startTime = slot.slot_start ? slot.slot_start.substring(0, 5) : '00:00';
        const endTime = slot.slot_end ? slot.slot_end.substring(0, 5) : '00:00';
        
        slotButton.textContent = `${startTime} - ${endTime}`;
        slotButton.className = 'slot-button';
        
        const isBooked = slot.is_booked === true || slot.is_booked === 1 || slot.is_booked === "1";
        
        if (isBooked) {
            slotButton.classList.add('booked', 'disabled');
            slotButton.disabled = true;
            slotButton.style.cursor = 'not-allowed';
            slotButton.style.backgroundColor = '#e0e0e0';
            slotButton.style.color = '#999';
            slotButton.style.borderColor = '#ccc';
            slotButton.style.opacity = '0.6';
            slotButton.style.pointerEvents = 'none';
            
            const statusText = slot.booking_status || 'Sudah dipesan';
            slotButton.title = `${statusText} - Tidak tersedia`;
            
        } else {
            slotButton.classList.add('available');
            slotButton.title = 'Klik untuk memilih slot';
            slotButton.style.cursor = 'pointer';
            
            slotButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                selectSlot(slot, slotButton);
            });
            
        }
        
        container.appendChild(slotButton);
    });
}

function selectSlot(slot, buttonElement) {
    
    const isBooked = slot.is_booked === true || slot.is_booked === 1 || slot.is_booked === "1";
    
    if (isBooked) {        
        const message = 'Slot waktu ini sudah dipesan dan tidak dapat dipilih.';
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Slot Tidak Tersedia',
                text: message,
                confirmButtonText: 'OK',
                confirmButtonColor: '#ff70b4'
            });
        } else {
            alert(message);
        }
        return;
    }
    
    if (buttonElement && buttonElement.disabled) {
        return;
    }
    
    document.querySelectorAll('.slot-button.selected').forEach(btn => {
        btn.classList.remove('selected');
    });
    
    if (buttonElement && !buttonElement.disabled && !buttonElement.classList.contains('booked')) {
        buttonElement.classList.add('selected');
    }
    
    selectedSlot = slot;
    
    const startTime = slot.slot_start || '00:00:00';
    const endTime = slot.slot_end || '00:00:00';
    
    const selectedStartTime = document.getElementById('selectedStartTime');
    const selectedEndTime = document.getElementById('selectedEndTime');
    
    if (selectedStartTime) {
        selectedStartTime.value = startTime.substring(0, 5);
    }
    if (selectedEndTime) {
        selectedEndTime.value = endTime.substring(0, 5);
    }
    
}

function changeMonthKlien(direction) {
    currentDateKlien.setMonth(currentDateKlien.getMonth() + direction);
    
    selectedDateKlien = null;
    resetSlotSelection();
    
    const selectedDateInput = document.getElementById('selectedDateInput');
    if (selectedDateInput) {
        selectedDateInput.value = '';
    }
    
    const slotsContainer = document.querySelector('.tombol-slot');
    if (slotsContainer) {
        slotsContainer.innerHTML = '';
    }
    
    generateCalendarKlien();
}

function toggleDropdown() {
    const dropdown = document.getElementById('layananDropdown');
    if (dropdown) {
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }
}

function updateTotalPrice() {
    const checkboxes = document.querySelectorAll('input[name="treatments[]"]:checked');
    let total = 0;
    
    checkboxes.forEach(checkbox => {
        const price = parseFloat(checkbox.dataset.price) || 0;
        total += price;
    });
    
    if (appliedVoucher && appliedVoucher.voucher_value) {
        total = Math.max(0, total - appliedVoucher.voucher_value);
    }
    
    const paymentElement = document.querySelector('.payment-akhir');
    if (paymentElement) {
        paymentElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
    }
    
    return total;
}

function validateVoucher() {
    const voucherInput = document.getElementById('formKupon');
    const voucherCode = voucherInput ? voucherInput.value.trim() : '';
    
    if (!voucherCode) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Kode Voucher Kosong',
                text: 'Silakan masukkan kode voucher terlebih dahulu.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ff70b4'
            });
        } else {
            alert('Silakan masukkan kode voucher terlebih dahulu.');
        }
        return;
    }

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Memvalidasi Voucher...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    const headers = {
        'Content-Type': 'application/json'
    };
    
    if (csrfToken) {
        headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
    }

    fetch(`${BASEURL}/reservation/validate-voucher`, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify({
            voucher_code: voucherCode,
            salon_id: SALON_ID
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (typeof Swal !== 'undefined') {
            Swal.close();
        }
        
        if (data.valid) {
            handleValidVoucher(voucherCode, data.voucher_value);
        } else {
            handleInvalidVoucher(data.message);
        }
    })
    .catch(error => {
        if (typeof Swal !== 'undefined') {
            Swal.close();
        }
        
        console.error('Error validating voucher:', error);
        appliedVoucher = null;
        updateTotalPrice();
        
        const errorMessage = 'Terjadi kesalahan saat memvalidasi voucher. Silakan coba lagi.';
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Server',
                text: errorMessage,
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        } else {
            alert(errorMessage);
        }
    });
}

function handleValidVoucher(voucherCode, voucherValue) {
    appliedVoucher = {
        voucher_code: voucherCode,
        voucher_value: voucherValue
    };
    const formattedVoucher = parseInt(voucherValue).toLocaleString('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Voucher Valid!',
            html: `<div style="font-size: 14px;">
                <p><strong>Kupon diterapkan:</strong> -Rp ${formattedVoucher}</p>
                </div>`,
            confirmButtonText: 'OK',
            confirmButtonColor: '#28a745',
            timer: 4000,
            timerProgressBar: true
        });
    }
    
    updateTotalPrice();
    updateVoucherUI(true);
}

function handleInvalidVoucher(message) {
    appliedVoucher = null;
    
    const errorMessage = message || 'Voucher tidak valid';
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Voucher Tidak Valid',
            text: errorMessage,
            confirmButtonText: 'OK',
            confirmButtonColor: '#dc3545'
        });
    } else {
        alert(errorMessage);
    }
    
    updateTotalPrice();
    updateVoucherUI(false);
}

function updateVoucherUI(isValid) {
    const voucherInput = document.getElementById('formKupon');
    const validateButton = document.querySelector('.validateButton');
    const removeButton = document.querySelector('.removeVoucherButton');
    
    if (voucherInput) {
        voucherInput.style.backgroundColor = isValid ? '#e8f5e8' : '';
        voucherInput.style.borderColor = isValid ? '#28a745' : '';
        voucherInput.readOnly = isValid;
    }
    
    if (validateButton) {
        validateButton.textContent = isValid ? 'Voucher Diterapkan' : 'Gunakan Kupon';
        validateButton.style.backgroundColor = isValid ? '#28a745' : '';
        validateButton.disabled = isValid;
    }
    
    if (removeButton) {
        removeButton.style.display = isValid ? 'inline-block' : 'none';
    }
}

function removeVoucher() {
    appliedVoucher = null;
    
    const voucherInput = document.getElementById('formKupon');
    if (voucherInput) {
        voucherInput.value = '';
    }
    
    updateVoucherUI(false);
    updateTotalPrice();
    
    const message = 'Voucher telah dihapus dari pesanan.';
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'info',
            title: 'Voucher Dihapus',
            text: message,
            confirmButtonText: 'OK',
            confirmButtonColor: '#ff70b4',
            timer: 2000,
            timerProgressBar: true
        });
    } else {
        alert(message);
    }
}

function validateForm() {
    const errors = [];
    
    const selectedTreatments = document.querySelectorAll('input[name="treatments[]"]:checked');
    if (selectedTreatments.length === 0) {
        errors.push('Silakan pilih minimal satu layanan');
    }
    
    if (!selectedDateKlien) {
        errors.push('Silakan pilih tanggal reservasi');
    }
    
    if (!selectedSlot) {
        errors.push('Silakan pilih slot waktu');
    }
    
    return errors;
}

function handleFormSubmission(e) {
    const errors = validateForm();
    
    if (errors.length > 0) {
        e.preventDefault();
        
        const errorMessage = '<div style="display: flex; align-items: center; justify-content: center; font-size: 14px;">' + errors.join('<br>') + '</div>';
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                html: errorMessage,
                confirmButtonText: 'OK',
                confirmButtonColor: '#ff70b4',
                allowOutsideClick: false,
                width: '430px',
                position: 'center',
            });
        } else {
            alert('Data belum lengkap:\n' + errors.join('\n'));
        }
        return false;
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Memproses Reservasi...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
    
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    generateCalendarKlien();
    
    const checkboxes = document.querySelectorAll('input[name="treatments[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateTotalPrice);
    });
    
    const form = document.getElementById('reservationForm');
    if (form) {
        form.addEventListener('submit', handleFormSubmission);
    }
    
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('layananDropdown');
        const dropdownBox = document.querySelector('.dropdown-box');
        
        if (dropdown && dropdownBox && 
            !dropdownBox.contains(event.target) && 
            !dropdown.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });
    
    updateTotalPrice();
});