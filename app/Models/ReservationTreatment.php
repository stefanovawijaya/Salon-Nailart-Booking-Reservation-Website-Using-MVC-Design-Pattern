<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ReservationTreatment extends Pivot
{
    protected $table = 'reservation_treatment';
    public $timestamps = true;
    protected $fillable = [
        'reservation_id',
        'treatment_id'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'reservation_id');
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class, 'treatment_id', 'treatment_id');
    }
}