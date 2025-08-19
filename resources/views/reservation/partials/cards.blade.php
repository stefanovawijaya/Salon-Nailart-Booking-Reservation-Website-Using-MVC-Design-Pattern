<!-- @if($reservations->count() > 0)
    @foreach($reservations as $reservation)
        <div class="booking-item" data-status="{{ $reservation->reservation_status }}">
            <div class="booking-header">
                <img src="{{ asset('img/salon/' . $reservation->salon->salon_image) }}" alt="{{ $reservation->salon->salon_name }}" class="salon-image">
                <div class="salon-info">
                    <h5>{{ $reservation->salon->salon_name }}</h5>
                    <p>{{ $reservation->salon->salon_location }}</p>
                </div>
                <div class="status-badge status-{{ strtolower(str_replace(' ', '-', $reservation->reservation_status)) }}">
                    {{ $reservation->reservation_status }}
                </div>
            </div>

            <div class="booking-details">
                <p><strong>Tanggal:</strong> {{ $reservation->reservation_date->format('d M Y') }}</p>
                <p><strong>Waktu:</strong>
                    @if($reservation->timeSlot)
                        {{ $reservation->timeSlot->start_time }} - {{ $reservation->timeSlot->end_time }}
                    @else
                        Waktu tidak tersedia
                    @endif
                </p>
                <p><strong>Layanan:</strong>
                    @foreach($reservation->treatments as $index => $treatment)
                        {{ $treatment->treatment_name }}@if(!$loop->last), @endif
                    @endforeach
                </p>
            </div>

            <div class="booking-footer">
                <div class="total-price">
                    Total: Rp {{ number_format($reservation->reservation_total_price, 2, ',', '.') }}
                </div>
                <a href="{{ route('reservation.detail', ['id' => $reservation->reservation_id]) }}" class="detail-btn">
                    Lihat Detail
                </a>
            </div>
        </div>
    @endforeach
@else
    <div class="no-bookings">
        <i class="fas fa-calendar-times"></i>
        <h5>Tidak ada pesanan</h5>
        <p>Belum ada data riwayat pesanan dengan status ini.</p>
    </div>
@endif -->