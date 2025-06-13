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
        'years_of_experience'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(RendezVous::class, 'dentist_id', 'user_id');
    }

    public function workingHours()
    {
        return $this->hasMany(PlageHoraire::class, 'dentist_id', 'user_id');
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo 
            ? asset('storage/'.$this->photo) 
            : asset('images/default-dentist.jpg');
    }

    public function getWorkingHoursAttribute()
    {
        return $this->workingHours()->get()->mapWithKeys(function($item) {
            return [
                strtolower($item->jour) => [
                    'start' => $item->heure_debut,
                    'end' => $item->heure_fin
                ]
            ];
        });
    }
}