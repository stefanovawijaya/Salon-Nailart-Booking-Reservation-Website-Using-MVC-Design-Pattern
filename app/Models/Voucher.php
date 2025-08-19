<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'vouchers';
    protected $primaryKey = 'voucher_id';

    protected $fillable = [
        'salon_id',
        'voucher_code',
        'voucher_value',
        'voucher_image'
    ];

    protected $casts = [
        'voucher_value' => 'decimal:2',
    ];

    public $timestamps = true;

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id', 'salon_id');
    }

}