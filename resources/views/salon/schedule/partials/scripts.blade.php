<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendar = document.getElementById('calendar');
    const savedDates = JSON.parse(calendar.dataset.bukaDates || '[]');
    
    const jadwalInput = document.getElementById('jadwal');
    const slotContainer = document.getElementById('slotContainer');
    const addSlotBtn = document.getElementById('addSlot');
    const statusToggle = document.querySelectorAll('.toggle-option');
    const formFields = document.querySelectorAll('input[name="open_time"], input[name="close_time"], input[name="slot_start[]"], input[name="slot_end[]"]');
    const submitBtn = document.querySelector('input[type="submit"]');
    const updateBtn = document.getElementById('updateBtn');
    
    const today = new Date();
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();
    let selectedDate = null;
    let isEditMode = false;

    function renderCalendar(month, year) {
        const calendarEl = document.getElementById('calendar');
        calendarEl.innerHTML = '';

        const firstDay = new Date(year, month).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        const monthName = new Date(year, month).toLocaleString('id-ID', { month: 'long' });
        document.getElementById('monthYear').textContent = monthName + ' ' + year;

        for (let i = 0; i < firstDay; i++) {
            const emptyDiv = document.createElement('div');
            emptyDiv.classList.add('empty');
            calendarEl.appendChild(emptyDiv);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const formatted = ("0" + date.getDate()).slice(-2) + "-" +
                              ("0" + (date.getMonth() + 1)).slice(-2) + "-" + date.getFullYear();

            const div = document.createElement('div');
            div.textContent = day;
            div.classList.add('date');
            div.style.cursor = 'pointer';
            
            const hasSavedData = savedDates.some(savedDate => {
                const dateStr = typeof savedDate === 'object' ? savedDate.schedule_date : savedDate;
                return dateStr === formatted;
            });
            
            if (hasSavedData) {
                const savedEntry = savedDates.find(savedDate => {
                    const dateStr = typeof savedDate === 'object' ? savedDate.schedule_date : savedDate;
                    return dateStr === formatted;
                });
                
                if (typeof savedEntry === 'object' && savedEntry.salon_status === 'Tutup') {
                    div.classList.add('closed-date');
                } else if (typeof savedEntry === 'object' && savedEntry.salon_status === 'Buka') {
                    div.classList.add('has-data');
                } else {
                    div.classList.add('has-data');
                }
            }
            
            const currentDate = new Date();
            currentDate.setHours(0, 0, 0, 0);
            date.setHours(0, 0, 0, 0);
            
            if (date >= currentDate) {
                div.addEventListener('click', function () {
                    document.querySelectorAll('.calendar .date').forEach(el => el.classList.remove('selected'));
                    div.classList.add('selected');
                    jadwalInput.value = formatted;
                    selectedDate = formatted;
                    
                    if (hasSavedData) {
                        fetchScheduleData(formatted);
                    } else {
                        resetForm();
                        isEditMode = false;
                        updateButtonVisibility();
                    }
                    
                });
                
                div.addEventListener('mouseenter', function() {
                    if (!div.classList.contains('selected')) {
                        div.style.backgroundColor = '#f0f0f0';
                    }
                });
                
                div.addEventListener('mouseleave', function() {
                    if (!div.classList.contains('selected')) {
                        div.style.backgroundColor = '';
                    }
                });
            } else {
                div.classList.add('past-date');
                div.style.cursor = 'not-allowed';
            }
            
            calendarEl.appendChild(div);
        }
    }

    function fetchScheduleData(date) {
        fetch('/salon/schedule/fetch', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ date: date })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error fetching schedule:', data.error);
                return;
            }
            
            populateForm(data);
            isEditMode = true;
            updateButtonVisibility();
            updateFormState();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function populateForm(data) {
        document.querySelectorAll('.toggle-option').forEach(btn => btn.classList.remove('active'));
        if (data.salon_status === 'Buka') {
            document.querySelector('.toggle-option.buka').classList.add('active');
            document.getElementById('salonStatus').value = 'Buka';
            
            if (data.open_time) {
                document.querySelector('input[name="open_time"]').value = data.open_time;
            }
            if (data.close_time) {
                document.querySelector('input[name="close_time"]').value = data.close_time;
            }
            
            slotContainer.innerHTML = '';
            
            if (data.slots && data.slots.length > 0) {
                data.slots.forEach(slot => {
                    addSlotToForm(slot.start_time, slot.end_time);
                });
            }
        } else {
            document.querySelector('.toggle-option.tutup').classList.add('active');
            document.getElementById('salonStatus').value = 'Tutup';
            
            document.querySelector('input[name="open_time"]').value = '';
            document.querySelector('input[name="close_time"]').value = '';
            slotContainer.innerHTML = '';
        }
    }

    function addSlotToForm(startTime = '', endTime = '') {
        const slotGroup = document.createElement('div');
        slotGroup.classList.add('slot-group');
        slotGroup.style.position = 'relative';

        slotGroup.innerHTML = `
            <div class="jam-mulai-selesai">
                <label style="font-size: 20px; display: block;">Slot Buka Jangkauan Jam</label>
                <input type="time" name="slot_start[]" value="${startTime}" required>
                <p style="display: inline-block;">-</p>
                <input type="time" name="slot_end[]" value="${endTime}" required>
            </div>
            <button type="button" class="remove-slot" style="background: red; color: white; border: none; padding: 5px 10px; margin-left: 10px; border-radius: 4px; cursor: pointer;">Hapus</button>
        `;

        const removeBtn = slotGroup.querySelector('.remove-slot');
        removeBtn.addEventListener('click', function() {
            slotGroup.remove();
        });

        slotContainer.appendChild(slotGroup);
    }

    function resetForm() {
        document.querySelectorAll('.toggle-option').forEach(btn => btn.classList.remove('active'));
        document.querySelector('.toggle-option.tutup').classList.add('active');
        document.getElementById('salonStatus').value = 'Tutup';
        
        document.querySelector('input[name="open_time"]').value = '';
        document.querySelector('input[name="close_time"]').value = '';
        slotContainer.innerHTML = '';
    }

    function updateButtonVisibility() {
        if (isEditMode) {
            submitBtn.style.display = 'none';
            updateBtn.style.display = 'inline-block';
        } else {
            submitBtn.style.display = 'inline-block';
            updateBtn.style.display = 'none';
        }
    }

    function updateFormState() {
        const isOpen = document.querySelector('.toggle-option.buka').classList.contains('active');
        const timeInputs = document.querySelectorAll('input[type="time"]');
        const addSlotButton = document.getElementById('addSlot');
        const activeSubmitBtn = isEditMode ? updateBtn : submitBtn;
        
        if (selectedDate && isOpen) {
            timeInputs.forEach(input => {
                input.disabled = false;
                input.style.opacity = '1';
            });
            if (addSlotButton) {
                addSlotButton.disabled = false;
                addSlotButton.style.opacity = '1';
            }
            if (activeSubmitBtn) {
                activeSubmitBtn.disabled = false;
                activeSubmitBtn.style.opacity = '1';
            }
        } else {
            timeInputs.forEach(input => {
                input.disabled = true;
                input.style.opacity = '0.5';
            });
            if (addSlotButton) {
                addSlotButton.disabled = true;
                addSlotButton.style.opacity = '0.5';
            }
            if (activeSubmitBtn) {
                activeSubmitBtn.disabled = !selectedDate;
                activeSubmitBtn.style.opacity = !selectedDate ? '0.5' : '1';
            }
        }
    }

    window.changeMonth = function (offset) {
        currentMonth += offset;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear -= 1;
        } else if (currentMonth > 11) {
            currentMonth = 0;
            currentYear += 1;
        }
        renderCalendar(currentMonth, currentYear);
    };

    renderCalendar(currentMonth, currentYear);

    window.toggleStatus = function (el) {
        document.querySelectorAll('.toggle-option').forEach(btn => btn.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('salonStatus').value = el.classList.contains('buka') ? 'Buka' : 'Tutup';
        
        updateFormState();
    };

    if (addSlotBtn) {
        addSlotBtn.addEventListener('click', function () {
            if (this.disabled) return;
            addSlotToForm();
        });
    }

    if (updateBtn) {
        updateBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = document.querySelector('form');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = '_method';
            hiddenInput.value = 'PUT';
            form.appendChild(hiddenInput);
            form.submit();
        });
    }

    if (submitBtn) {
        submitBtn.addEventListener('click', function (e) {
            const status = document.getElementById('salonStatus').value;
            const slotGroups = document.querySelectorAll('#slotContainer .slot-group');

            const validSlotExists = Array.from(slotGroups).some(group => {
                const start = group.querySelector('input[name="slot_start[]"]');
                const end = group.querySelector('input[name="slot_end[]"]');
                return start && end && start.value && end.value;
            });

            if (status === 'Buka' && !validSlotExists) {
                alert('Minimal satu slot jam buka harus diisi.');
                e.preventDefault();
            }
        });
    }
    
    updateButtonVisibility();
    updateFormState();
});
</script>