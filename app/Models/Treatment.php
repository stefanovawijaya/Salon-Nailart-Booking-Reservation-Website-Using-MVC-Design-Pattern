<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;

    protected $table = 'treatments';
    protected $primaryKey = 'treatment_id';

    public $timestamps = true;

    protected $fillable = [
        'salon_id',
        'treatment_name',
        'treatment_price',
        'treatment_image'
    ];

    protected $casts = [
        'treatment_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id', 'salon_id');
    }

    public function reservationTreatments()
    {
        return $this->hasMany(ReservationTreatment::class, 'treatment_id', 'treatment_id');
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_treatment', 'treatment_id', 'reservation_id');
    }
}