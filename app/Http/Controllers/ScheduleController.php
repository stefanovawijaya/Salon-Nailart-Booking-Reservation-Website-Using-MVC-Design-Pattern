<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\ScheduleSlot;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function form()
    {
        $salon_id = Auth::guard('salon')->id();

        $bukaDates = Schedule::where('salon_id', $salon_id)
            ->select('schedule_date', 'salon_status')
            ->get()
            ->map(function($schedule) {
                return [
                    'schedule_date' => Carbon::parse($schedule->schedule_date)->format('d-m-Y'),
                    'salon_status' => $schedule->salon_status
                ];
            })
            ->toArray();

        return view('salon.schedule.form', compact('bukaDates'));
    }

    public function fetchSchedule(Request $request)
    {
        $salon_id = Auth::guard('salon')->id();
        $date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
        
        $schedule = Schedule::where('salon_id', $salon_id)
            ->where('schedule_date', $date)
            ->with('scheduleSlots')
            ->first();
            
        if (!$schedule) {
            return response()->json(['error' => 'Schedule not found'], 404);
        }
        
        $slots = $schedule->scheduleSlots->map(function($slot) {
            return [
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time
            ];
        });
        
        return response()->json([
            'salon_status' => $schedule->salon_status,
            'open_time' => $schedule->open_time !== '00:00:00' ? $schedule->open_time : null,
            'close_time' => $schedule->close_time !== '00:00:00' ? $schedule->close_time : null,
            'slots' => $slots
        ]);
    }

    public function save(Request $req)
    {
        $req->validate([
            'schedule_date' => 'required|date_format:d-m-Y',
            'salon_status' => 'required|in:Buka,Tutup',
            'open_time' => 'required_if:salon_status,Buka|date_format:H:i',
            'close_time' => 'required_if:salon_status,Buka|date_format:H:i',
            'slot_start' => 'required_if:salon_status,Buka|array|min:1',
            'slot_end' => 'required_if:salon_status,Buka|array|min:1',
            'slot_start.*' => 'required_with:slot_end.*|date_format:H:i',
            'slot_end.*' => 'required_with:slot_start.*|date_format:H:i',
        ]);

        $salon_id = Auth::guard('salon')->id();
        $schedule_date = Carbon::createFromFormat('d-m-Y', $req->schedule_date)->format('Y-m-d');

        $data = [
            'salon_id' => $salon_id,
            'schedule_date' => $schedule_date,
            'salon_status' => $req->salon_status,
            'open_time' => $req->salon_status === 'Buka' ? $req->open_time : '00:00:00',
            'close_time' => $req->salon_status === 'Buka' ? $req->close_time : '00:00:00',
        ];

        $schedule = Schedule::updateOrCreate(
            ['salon_id' => $salon_id, 'schedule_date' => $schedule_date],
            $data
        );

        if ($req->salon_status === 'Buka') {
            $schedule->scheduleSlots()->delete();

            if ($req->slot_start) {
                foreach ($req->slot_start as $i => $start) {
                    if (!isset($req->slot_end[$i])) continue;

                    ScheduleSlot::create([
                        'schedule_id' => $schedule->schedule_id,
                        'start_time' => $start,
                        'end_time' => $req->slot_end[$i],
                    ]);
                }
            }
        } else {
            $schedule->scheduleSlots()->delete();
        }

        return back();
    }
}