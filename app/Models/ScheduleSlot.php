<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ScheduleSlot extends Model
{
    use HasFactory;

    protected $table = 'schedule_slots';
    protected $primaryKey = 'schedule_slot_id';

    public $timestamps = true;

    protected $fillable = [
        'schedule_id',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'schedule_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'schedule_slot_id', 'schedule_slot_id');
    }
}