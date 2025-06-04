<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dentist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialty',
        'bio',
        'license_number',
        'photo',
        'years_of_experience',
        'working_hours'
    ];

    protected $casts = [
        'working_hours' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(RendezVous::class);
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo 
            ? asset('storage/'.$this->photo) 
            : asset('images/default-dentist.jpg');
    }
}