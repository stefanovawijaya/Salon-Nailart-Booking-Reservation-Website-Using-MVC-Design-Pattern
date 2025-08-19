<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'gallery';
    protected $primaryKey = 'gallery_id';
    public $timestamps = true;
    
    protected $fillable = [
        'salon_id',
        'nailart_desc',
        'nailart_image',
        'nailart_name'
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id', 'salon_id');
    }
}