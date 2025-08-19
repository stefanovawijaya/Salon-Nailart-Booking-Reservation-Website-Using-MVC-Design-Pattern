<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Salon extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'salons';
    protected $primaryKey = 'salon_id';
    public $timestamps = true;
    
    protected $fillable = [
        'salon_email',
        'salon_password',
        'salon_name',
        'salon_description',
        'salon_operational_hour',
        'salon_location',
        'salon_phonenumber',
        'salon_pinpoint',
        'salon_image'
    ];

    protected $hidden = [
        'salon_password',
    ];

    public function getAuthIdentifierName()
    {
        return 'salon_id';
    }

    public function getAuthIdentifier()
    {
        return $this->salon_id;
    }

    public function getAuthPassword()
    {
        return $this->salon_password;
    }

    public function getAuthPasswordName()
    {
        return 'salon_password';
    }

    public function getRememberTokenName()
    {
        return null;
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'salon_id', 'salon_id');
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'salon_id', 'salon_id');
    }

    public function treatments()
    {
        return $this->hasMany(Treatment::class, 'salon_id', 'salon_id');
    }
    
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'salon_id', 'salon_id');
    }
}