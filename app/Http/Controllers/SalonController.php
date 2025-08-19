<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Salon;
use App\Models\Gallery;
use App\Models\Treatment;
use App\Models\Voucher;
use App\Models\Reservation;

class SalonController extends Controller
{
    public function lists()
    {
        $salons = Salon::get();
        return view('home.salon', compact('salons'));
    }
    
    public function detail($salon_id)
    {
        $salon = Salon::findOrFail($salon_id);

        $gallery = Gallery::where('salon_id', $salon_id)->orderByDesc('created_at')->get();
        $treatments = Treatment::where('salon_id', $salon_id)->orderByDesc('created_at')->get();
        $vouchers = Voucher::where('salon_id', $salon_id)->orderByDesc('created_at')->get();

        return view('home.salon_detail', [
            'salon' => $salon,
            'gallery' => $gallery,
            'treatments' => $treatments,
            'vouchers' => $vouchers,
        ]);
    }

    public function homePage()
    {
        $salon = Auth::guard('salon')->user();

        $reservations = Reservation::with('client')
            ->where('salon_id', $salon->salon_id)
            ->orderByDesc('created_at')
            ->get();

        return view('salon.index', [
            'title' => 'Home Salon',
            'salon' => $salon,
            'reservations' => $reservations,
        ]);
    }

    public function getSalonReservations()
    {
        try {
            $salon = Auth::guard('salon')->user();
            
            $reservations = Reservation::with('client')
                ->where('salon_id', $salon->salon_id)
                ->orderByDesc('created_at')
                ->get();

            $formattedReservations = $reservations->map(function ($reservation) {
                return [
                    'id' => $reservation->reservation_id,
                    'client_name' => $reservation->client?->client_name ?? 'Unknown',
                    'reservation_status' => $reservation->reservation_status ?? 'Unknown',
                    'reservation_total_price' => $reservation->reservation_total_price ?? 0,
                    'reservation_date' => $reservation->reservation_date,
                    'reservation_start' => $reservation->start_time,
                    'reservation_created_at' => $reservation->created_at,
                ];
            });

            return response()->json($formattedReservations);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function listClients()
    {
        $salon = Auth::guard('salon')->user();
    
        $reservations = Reservation::with('client')
            ->where('salon_id', $salon->salon_id)
            ->orderByDesc('created_at')
            ->get();

        return view('salon.reservation.list', compact('reservations'));
    }
    
    public function account()
    {
        $salon = Auth::guard('salon')->user();
        
        $gallery = Gallery::where('salon_id', $salon->salon_id)->get();
        $treatments = Treatment::where('salon_id', $salon->salon_id)->get();
        $vouchers = Voucher::where('salon_id', $salon->salon_id)->get();

        return view('salon.account', compact('salon', 'gallery', 'treatments', 'vouchers'));
    }

    public function edit()
    {
        $salon = Auth::guard('salon')->user();
        
        
        return view('salon.edit', compact('salon'));
    }

    public function update(Request $request)
    {
        $salon = Auth::guard('salon')->user();

        $request->validate([
            'salon_name' => 'required|string|max:255',
            'salon_description' => 'nullable|string|max:255',
            'salon_operational_hour' => 'nullable|string|max:255',
            'salon_location' => 'nullable|string|max:255',
            'salon_phonenumber' => 'nullable|string|max:20',
            'salon_pinpoint' => 'nullable|url',
            'salon_image' => 'nullable|image|mimes:jpeg,png,jpg|max:16384',
        ]);

        $salon->salon_name = $request->salon_name;
        $salon->salon_description = $request->salon_description;
        $salon->salon_operational_hour = $request->salon_operational_hour;
        $salon->salon_location = $request->salon_location;
        $salon->salon_phonenumber = $request->salon_phonenumber;
        $salon->salon_pinpoint = $request->salon_pinpoint;

        if ($request->hasFile('salon_image')) {
            $oldImage = public_path('img/salon/' . $salon->salon_image);

            if ($salon->salon_image && $salon->salon_image !== 'default.jpg' && File::exists($oldImage)) {
                File::delete($oldImage);
            }

            $image = $request->file('salon_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/salon'), $filename);

            $salon->salon_image = $filename;
        }

        $salon->save();

        return redirect()->route('salon.account');
    }
}