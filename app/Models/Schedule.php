<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';
    protected $primaryKey = 'schedule_id';

    public $timestamps = true;

    protected $fillable = [
        'salon_id',
        'schedule_date',
        'open_time',
        'close_time',
        'salon_status'
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id', 'salon_id');
    }

    public function scheduleSlots()
    {
        return $this->hasMany(ScheduleSlot::class, 'schedule_id', 'schedule_id');
    }

    public function reservations()
    {
        return $this->hasManyThrough(Reservation::class, ScheduleSlot::class, 'schedule_id', 'schedule_slot_id', 'schedule_id', 'id');
    }
}