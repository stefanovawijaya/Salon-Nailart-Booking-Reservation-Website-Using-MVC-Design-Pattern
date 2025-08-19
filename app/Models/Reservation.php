<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    protected $primaryKey = 'reservation_id';
    
    protected $fillable = [
        'client_id',
        'salon_id',
        'reservation_date',
        'reservation_start',
        'reservation_end',
        'reservation_status',
        'reservation_total_price',
        'voucher_id',
        'voucher_code',
        'voucher_value'
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'reservation_start' => 'datetime',
        'reservation_end' => 'datetime',
        'reservation_total_price' => 'decimal:2',
        'voucher_value' => 'integer'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id', 'salon_id');
    }

    public function treatments()
    {
        return $this->belongsToMany(Treatment::class, 'reservation_treatment', 'reservation_id', 'treatment_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'voucher_id');
    }
}