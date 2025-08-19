<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salon;
use App\Models\Treatment;
use App\Models\Schedule;
use App\Models\ScheduleSlot;
use App\Models\Reservation;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function history(Request $request)
    {
        $clientId = Auth::guard('client')->id();
        $status = $request->get('status', 'semua');

        $query = Reservation::with('salon')
            ->where('client_id', $clientId)
            ->orderBy('reservation_date', 'desc');

        if ($status !== 'semua') {
            $normalizedStatus = match(strtolower($status)) {
                'sedangberlangsung' => 'Sedang Berlangsung',
                'selesai' => 'Selesai',
                'dibatalkan' => 'Dibatalkan',
                default => null
            };

            if ($normalizedStatus) {
                $query->where('reservation_status', $normalizedStatus);
            }
        }

        $reservations = $query->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $reservations->map(function ($r) {
                    return [
                        'reservation_id' => $r->reservation_id,
                        'salon_name' => $r->salon->salon_name ?? 'Salon Tidak Ditemukan',
                        'salon_image' => $r->salon->salon_image ?? null,
                        'reservation_created_at' => $r->created_at,
                        'reservation_status' => $r->reservation_status,
                        'reservation_date' => $r->reservation_date,
                        'reservation_start' => $r->reservation_start,
                        'reservation_end' => $r->reservation_end,
                        'reservation_total_price' => $r->reservation_total_price,
                    ];
                })
            ]);
        }

        return view('reservation.history');
    }

    public function create(Salon $salon)
    {
        $treatments = $salon->treatments;
        if (!Auth::guard('client')->check()) {
            return redirect()->route('client.login');
        }

        $client = Auth::guard('client')->user();

        if (empty($client->client_name) || empty($client->client_phonenumber)) {
            return redirect()->route('client.edit');
        }

        return view('reservation.create', compact('salon', 'treatments', 'client'));
    }

    public function showDetail($reservation_id)
    {
        $reservation = Reservation::with(['salon', 'client', 'treatments'])
            ->where('reservation_id', $reservation_id)
            ->where('client_id', Auth::guard('client')->id())
            ->firstOrFail();

        $timeLabel = $reservation->reservation_start && $reservation->reservation_end ? 
            $reservation->reservation_start->format('H:i') . ' - ' . $reservation->reservation_end->format('H:i') : 
            'Waktu tidak tersedia';

        return view('reservation.detail', compact('reservation', 'timeLabel'));
    }

    public function salonDetail($reservation_id)
    {
        $salon = Auth::guard('salon')->user();

        $reservation = Reservation::with(['client', 'salon', 'treatments'])
            ->where('reservation_id', $reservation_id)
            ->where('salon_id', $salon->salon_id)
            ->firstOrFail();

        $treatments = $reservation->treatments;

        return view('salon.reservation.detail', [
            'reservation' => $reservation,
            'treatments' => $treatments
        ]);
    }

    public function getBukaDates($salonId, $year, $month)
    {
        $schedules = Schedule::where('salon_id', $salonId)
            ->whereYear('schedule_date', $year)
            ->whereMonth('schedule_date', $month)
            ->where('salon_status', 'buka')
            ->get(['schedule_date', 'salon_status']);

        return response()->json($schedules);
    }

    public function getSlots($date, $salonId)
    {
        try {            
            $schedule = Schedule::where('salon_id', $salonId)
                ->where('schedule_date', $date)
                ->where('salon_status', 'buka')
                ->first();

            if (!$schedule) {
                return response()->json(['message' => 'Tidak tersedia', 'slots' => []]);
            }

            $slots = ScheduleSlot::where('schedule_id', $schedule->schedule_id)
                ->orderBy('start_time', 'asc')
                ->get();

            $bookedReservations = Reservation::where('salon_id', $salonId)
                ->where('reservation_date', $date)
                ->whereIn('reservation_status', [
                    'Sedang Berlangsung', 
                    'Selesai'
                ])
                ->whereNotIn('reservation_status', ['Dibatalkan'])
                ->get(['reservation_start', 'reservation_end', 'reservation_status']);

            $processedSlots = [];
            foreach ($slots as $slot) {
                $processedSlot = [
                    'slot_id' => $slot->schedule_slot_id,
                    'slot_start' => $slot->start_time,
                    'slot_end' => $slot->end_time,
                    'is_booked' => false,
                    'booking_status' => null
                ];
                
                foreach ($bookedReservations as $reservation) {
                    $slotStart = \Carbon\Carbon::parse($slot->start_time);
                    $slotEnd = \Carbon\Carbon::parse($slot->end_time);
                    $reservationStart = \Carbon\Carbon::parse($reservation->reservation_start);
                    $reservationEnd = \Carbon\Carbon::parse($reservation->reservation_end);
                    
                    $exactMatch = $slotStart->eq($reservationStart) && $slotEnd->eq($reservationEnd);
                    
                    $hasOverlap = $slotStart->lt($reservationEnd) && $slotEnd->gt($reservationStart);
                    
                    if ($exactMatch || $hasOverlap) {
                        $processedSlot['is_booked'] = true;
                        $processedSlot['booking_status'] = $reservation->reservation_status;
                        break;
                    }
                }
                
                $processedSlots[] = $processedSlot;
            }

            return response()->json([
                'success' => true,
                'slots' => $processedSlots,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error', 
                'message' => $e->getMessage(),
                'slots' => []
            ], 500);
        }
    }

    public function validateVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
            'salon_id' => 'required|exists:salons,salon_id'
        ]);

        $voucher = Voucher::where('voucher_code', $request->voucher_code)
            ->where('salon_id', $request->salon_id)
            ->first();

        if ($voucher) {
            return response()->json([
                'valid' => true,
                'voucher_value' => $voucher->voucher_value,
                'message' => 'Voucher valid!'
            ]);
        } else {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher tidak valid atau tidak tersedia untuk salon ini.'
            ]);
        }
    }

    public function store(Request $request)
    {
        $client = Auth::guard('client')->user();

        if (empty($client->client_name) || empty($client->client_phonenumber)) {
            return back()->withInput();
        }

        $request->validate([
            'client_id' => 'required|exists:clients,client_id',
            'salon_id' => 'required|exists:salons,salon_id',
            'reservation_date' => 'required|date_format:d-m-Y',
            'reservation_start' => 'required|date_format:H:i',
            'reservation_end' => 'required|date_format:H:i|after:reservation_start',
            'treatments' => 'required|array|min:1',
            'treatments.*' => 'exists:treatments,treatment_id',
            'formKupon' => 'nullable|string',
        ], [
            'treatments.required' => 'Silakan pilih minimal satu layanan.',
            'treatments.min' => 'Silakan pilih minimal satu layanan.',
            'reservation_date.required' => 'Silakan pilih tanggal reservasi.',
            'reservation_start.required' => 'Silakan pilih waktu mulai.',
            'reservation_end.required' => 'Silakan pilih waktu selesai.',
            'reservation_end.after' => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        $dateFormatted = \Carbon\Carbon::createFromFormat('d-m-Y', $request->reservation_date)->format('Y-m-d');
        
        $schedule = Schedule::where('salon_id', $request->salon_id)
            ->where('schedule_date', $dateFormatted)
            ->where('salon_status', 'buka')
            ->first();

        if (!$schedule) {
            return back()->withErrors(['reservation_date' => 'Tanggal yang dipilih tidak tersedia.'])->withInput();
        }

        $requestedStart = $request->reservation_start . ':00';
        $requestedEnd = $request->reservation_end . ':00';

        $validSlot = ScheduleSlot::where('schedule_id', $schedule->schedule_id)
            ->where('start_time', $requestedStart)
            ->where('end_time', $requestedEnd)
            ->first();

        if (!$validSlot) {
            return back()->withErrors(['reservation_start' => 'Slot waktu yang dipilih tidak valid.'])->withInput();
        }

        $conflictingReservation = Reservation::where('salon_id', $request->salon_id)
            ->where('reservation_date', $dateFormatted)
            ->where(function($query) use ($requestedStart, $requestedEnd) {
                $query->where(function($q) use ($requestedStart, $requestedEnd) {
                    $q->where('reservation_start', '<', $requestedEnd)
                    ->where('reservation_end', '>', $requestedStart);
                });
            })
            ->first();

        if ($conflictingReservation) {
            return back()->withErrors(['reservation_start' => 'Slot waktu yang dipilih sudah dipesan.'])->withInput();
        }

        $treatments = Treatment::whereIn('treatment_id', $request->treatments)->get();
        $totalTreatmentPrice = $treatments->sum('treatment_price');
        $voucherDiscount = 0;
        $validVoucher = null;

        if ($request->formKupon) {
            $validVoucher = Voucher::where('voucher_code', $request->formKupon)
                ->where('salon_id', $request->salon_id)
                ->first();

            if (!$validVoucher) {
                return back()->withErrors(['formKupon' => 'Kode voucher tidak valid atau tidak tersedia untuk salon ini.'])->withInput();
            }

            $voucherDiscount = $validVoucher->voucher_value;
        }

        $finalPrice = max(0, $totalTreatmentPrice - $voucherDiscount);

        DB::beginTransaction();
        try {
            $reservation = new Reservation();
            $reservation->client_id = $request->client_id;
            $reservation->salon_id = $request->salon_id;
            $reservation->reservation_date = $dateFormatted;
            $reservation->reservation_start = $requestedStart;
            $reservation->reservation_end = $requestedEnd;
            $reservation->reservation_total_price = $finalPrice;
            
            if ($validVoucher) {
                $reservation->voucher_id = $validVoucher->voucher_id;
                $reservation->voucher_code = $validVoucher->voucher_code;
                $reservation->voucher_value = $validVoucher->voucher_value;
            }
            
            $reservation->save();

            foreach ($request->treatments as $treatmentId) {
                $reservation->treatments()->attach($treatmentId, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();
        
            return redirect()->route('reservation.detail', ['id' => $reservation->reservation_id])
                ->with('reservation_success', '1');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat reservasi. Silakan coba lagi.'])->withInput();
        }
    }

    public function updateStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        if (!$id || !$status) {
            return response()->json(['success' => false, 'error' => 'Tidak ditemukan ID atau status'], 400);
        }

        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['success' => false, 'error' => 'Reservasi tidak ditemukan'], 404);
        }

        $reservation->reservation_status = $status;

        if ($reservation->save()) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'error' => 'Gagal untuk memperbarui'], 500);
    }
}