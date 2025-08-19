<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Client extends Authenticatable
{
    use Notifiable;

    protected $table = 'clients';
    protected $primaryKey = 'client_id';
    
    protected $fillable = [
        'client_email',
        'client_password',
        'client_name',
        'client_phonenumber',
        'client_image_icon'
    ];

    protected $hidden = [
        'client_password',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'client_id', 'client_id');
    }

    public function getAuthIdentifierName()
    {
        return 'client_id';
    }

    public function getAuthIdentifier()
    {
        return $this->client_id;
    }

    public function getAuthPassword()
    {
        return $this->client_password;
    }

    public function getPasswordAttribute()
    {
        return $this->attributes['client_password'];
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['client_password'] = $value;
    }

    public function username()
    {
        return 'client_email';
    }
}