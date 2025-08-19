<style>
.calendar-container-klien {
    display: flex;
    flex-direction: column;
    background-color: white;
    border-radius: 10px;
    max-width: 380px;
    margin: auto;
    margin-top: 30px;
}

.month-nav-klien {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.month-nav-klien button{
    border: none;
    color: white;
    background-color: rgba(255, 112, 180, 1);
    font-size: 24px;
    border-radius: 5px;
    padding: 5px 10px;
}

.month-nav-klien button:hover {
    background-color: rgba(174, 101, 212, 1);
    border-radius: 4px;
}

.kalender-hari-klien{
    font-size: 20px; 
    line-height: 0.1;
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 10px;
    text-align: center;
}


.calendar-klien {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 10px;
    margin-top: 5px;
    
}
.calendar-klien div {
    text-align: center;
    padding: 10px;
    cursor: pointer;
    font-size: 20px;
    background-color: #f9f9f9;
    border-radius: 3px;
}

.calendar-day-klien.empty {
    border: none;
    cursor: default;
}

.calendar-day-klien.past-date {
    color: #ccc;
    cursor: not-allowed;
    background-color: #f9f9f9;
}

.calendar-day-klien.unavailable {
    color: black;
    cursor: not-allowed;
    background-color: rgba(221, 221, 221, 1) !important;
}

.calendar-day-klien.available {
    background-color: rgba(255, 229, 242, 1);
    color: black;
    cursor: pointer;
}

.calendar-day-klien.available:hover {
    background-color: rgba(255, 229, 242, 0.8);
    transform: scale(1.05);
}

.calendar-day-klien.selected {
    background-color: #ff70b4;
    color: white;
    font-weight: bold;
}

.calendar-day-klien.today {
    border: 3px solid rgba(174, 101, 212, 1);
    font-weight: bold;
}


.tombol-slot{
    padding: 15px;
    text-align: center;
}

.slot-button{
    background-color: rgba(255, 229, 242, 1);
    border: 2px solid rgba(255, 112, 180, 1);
    border-radius: 8px;
    padding: 5px 15px;
    margin: 10px;
    pointer-events: auto !important;
    z-index: 10 !important;
    position: relative;
}

.slot-button.available:hover {
    background-color: #ff70b4;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(255, 112, 180, 0.3);
}

.slot-button.selected {
    background-color: rgba(255, 112, 180, 1) !important;
    border: 2px solid rgba(174, 101, 212, 1) !important;
    color: white;
    font-weight: bold;
}

.slot-button.booked {
    background-color: #e0e0e0 !important;
    color: #999 !important;
    border-color: #ccc !important;
    cursor: not-allowed !important;
    opacity: 0.6 !important;
    pointer-events: none !important;
}

.slot-button.booked:hover {
    transform: none !important;
    box-shadow: none !important;
}

.loading-slots, .no-slots, .error-slots {
    text-align: center;
    padding: 20px;
    font-style: italic;
    color: #666;
    width: 100%;
}

.error-slots {
    color: #dc3545 !important;
    font-weight: 500;
}

.layanan-container {
    position: relative;
    margin-bottom: 20px;
}

.dropdown-box {
    border: 2px solid rgba(255, 112, 180, 1);
    border-radius: 8px;
    padding: 12px 14px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dropdown-list {
    margin-top: 6px;
    border: 2px solid rgba(255, 112, 180, 1);
    border-radius: 10px;
    overflow: hidden;
    display: none;
    flex-direction: column;
}

.dropdown-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 12px;
    border-bottom: 1px solid rgba(255, 112, 180, 1);
    color: rgba(255, 112, 180, 1);
    font-size: 14px;
}


.dropdown-item:last-child {
    border-bottom: none;
}

.layanan-nama {
    font-weight: 500;
}

.layanan-harga {
    color: #ff70b4;
    font-weight: bold;
}

.form-kupon{
    display: flex;
    align-items: center;
    margin-top: 10px;
}

.form-kupon input[type=text]{
    width: 100%;
    border: 2px solid rgba(255, 112, 180, 1);
    border-radius: 8px;
    padding: 12px 20px;
    height: 45px;
    margin: 8px 0px;
    font-size: 16px;
}

.form-kupon img{
    width: 32px;
    height: 32px;
    margin-right: 10px;
}

.validateButton{
    background-color: rgba(174, 101, 212, 1);
    color: white;
    border: none;
    border-radius: 5px;
    padding: 6px 8px;
    margin-left: 4px;
}

.validateButton:hover {
    background-color: #e55ca0;
}

.removeVoucherButton{
    display: none; 
    background-color: #dc3545;
    border-radius: 5px;
    padding: 6px 8px;
    margin-left: 4px;
}

.total-payment{
    display: flex;
    align-items: center;
}

.total-payment img{
    display: inline-block;
    margin-right: 15px;
}

.total-payment p{
    display: inline-block;
}

.payment-akhir{
    margin-left: auto;
    font-size: 22px; 
    font-weight: bold; 
    color: rgba(255, 112, 180, 1);
}
</style>