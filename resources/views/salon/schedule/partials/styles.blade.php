<style>
    .form-informasi-postingan {
        background-color: rgba(255, 112, 180, 1);
        color: white;
        font-weight: bold;
        font-size: 24px;
        border-radius: 8px 8px 0px 0px;
        padding: 15px 45px;
        width: 100%;
    }

    .form-informasi-isi {
        background-color: white;
        padding: 20px 45px 30px 45px;
        border-radius: 0px 0px 8px 8px;
        width: 100%;
    }

    .status-toggle {
        display: flex;
        border: 2px solid rgba(255, 112, 180, 1);
        border-radius: 8px;
        overflow: hidden;
        width: 100%;
        background-color: white;
        margin-bottom: 8px;
    }

    .toggle-option {
        padding: 10px 20px;
        flex: 1;
        text-align: center;
        cursor: pointer;
        font-weight: 500;
        color: black;
        background-color: white;
        transition: all 0.2s ease;
        font-size: 20px;
    }

    .toggle-option.active {
        background-color: rgba(255, 112, 180, 1);
        color: white;
    }

    .calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        margin-top: 5px;
    }

    .calendar .date {
        text-align: center;
        transition: all 0.2s ease;
        font-weight: 500;
        min-height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(255, 229, 242, 1);
        cursor: pointer;
    }

    .calendar .date:hover {
        background-color: rgba(174, 101, 212, 1) !important;
        transform: translateY(-1px);
    }

    .calendar .date.selected {
        background-color: rgba(255, 112, 180, 1) !important;
        color: white !important;
        font-weight: bold;
        border-color: rgba(255, 112, 180, 1) !important;
        box-shadow: 0 4px 8px rgba(255, 112, 180, 0.3);
    }

    .calendar .date.past-date {
         color: #ccc;
        cursor: not-allowed !important;
        background-color: rgba(255, 229, 242, 1);
        border-color: #e0e0e0;
    }

    .calendar .date.past-date:hover {
        background-color: #f5f5f5 !important;
        transform: none !important;
        box-shadow: none !important;
        border-color: #e0e0e0 !important;
    }

    .calendar .date.has-data {
        text-decoration: underline;
        font-weight: bold;
        background-color: #e8f5e8;
        border-color: #28a745;
        color: #155724;
    }

    .calendar .date.has-data:hover {
        background-color: #d1edcc;
        border-color: #28a745;
    }

    .calendar .date.closed-date {
        background-color: #6c757d !important;
        color: white !important;
        font-weight: bold;
        border-color: #5a6268 !important;
        cursor: pointer;
    }

    .calendar .date.closed-date:hover {
        background-color: #5a6268 !important;
        border-color: #545b62 !important;
    }

    .calendar .empty {
        min-height: 40px;
    }

    #jadwal {
        background-color: #f8f9fa;
        border: 2px solid rgba(255, 112, 180, 1);
        padding: 12px 16px;
        border-radius: 6px;
        font-size: 16px;
        width: 100%;
        margin-bottom: 16px;
        transition: all 0.2s ease;
    }

    #jadwal:focus {
        outline: none;
        border-color: rgba(255, 112, 180, 1);
        box-shadow: 0 0 8px rgba(255, 112, 180, 0.2);
        background-color: white;
    }

    input[type="time"]:disabled,
    button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    input[type="submit"]:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background-color: #6c757d;
    }

    .grey {
        color: gray;
        background-color: #f2f2f2;
    }

    .underlined {
        text-decoration: underline;
        font-weight: bold;
    }

.calendar-container {
    display: flex;
    flex-direction: column;
    background-color: rgba(255, 229, 242, 1);
    border-radius: 10px;
    max-width: 360px;
    float: left;
}

    .month-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .month-nav button {
        background-color: rgba(255, 112, 180, 1);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        font-size: 24px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .month-nav button:hover {
        background-color: rgba(255, 112, 180, 0.8);
    }

    .kalender-hari {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        margin-bottom: 10px;
    }

    .kalender-hari p {
        text-align: center;
        margin: 0;
        padding: 8px;
    }

    .slot-group {
        margin-bottom: 15px;
    }

    .remove-slot {
        background: #dc3545 !important;
        color: white !important;
        border: none !important;
        padding: 12px 47px !important;
        border-radius: 8px !important;
        cursor: pointer !important;
        font-size: 20px !important;
        margin-left: 0px !important;
    }

    .remove-slot:hover {
        background: #c82333 !important;
    }

.today {
    border: 3px solid rgba(174, 101, 212, 1);
    font-weight: bold;
}
</style>