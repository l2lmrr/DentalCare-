<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlageHoraire extends Model
{
    use HasFactory;
    
    protected $table = 'plages_horaires'; // Add this line

    protected $fillable = [
        'dentist_id',
        'jour',
        'heure_debut',
        'heure_fin'
    ];

    protected $casts = [
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'jour' => 'string'
    ];

    public function dentist()
    {
        return $this->belongsTo(User::class, 'dentist_id')->with('dentist');
    }

    public function dentistInfo()
    {
        return $this->belongsTo(Dentist::class, 'dentist_id', 'user_id');
    }

    public function getDayNameAttribute()
    {
        $days = [
            'lundi' => 'Monday',
            'mardi' => 'Tuesday',
            'mercredi' => 'Wednesday',
            'jeudi' => 'Thursday',
            'vendredi' => 'Friday',
            'samedi' => 'Saturday',
            // Also handle English day names
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday'
        ];
        
        return $days[strtolower($this->jour)] ?? ucfirst($this->jour);
    }
}