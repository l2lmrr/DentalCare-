<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the appointments where this user is the patient
     */
    public function appointments()
    {
        return $this->hasMany(RendezVous::class, 'patient_id');
    }

    /**
     * Get the appointments where this user is the dentist
     */
    public function dentistAppointments()
    {
        return $this->hasMany(RendezVous::class, 'dentist_id');
    }

    public function hasRole($roles)
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        return in_array($this->role, $roles);
    }

    public function dentist()
    {
        return $this->hasOne(Dentist::class);
    }

    public function workingHours()
    {
        return $this->hasMany(PlageHoraire::class, 'dentist_id');
    }

    // Check roles
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPatient()
    {
        return $this->role === 'patient';
    }

    public function isDentist()
    {
        return $this->role === 'praticien';
    }

    public function getDentistData()
    {
        return $this->dentist;
    }

    // Accessor for photo
    public function getPhotoUrlAttribute()
    {
        return $this->dentist && $this->dentist->photo 
            ? asset('storage/'.$this->dentist->photo) 
            : asset('images/default-profile.jpg');
    }

    // Get working hours as structured array (compatibility)
    public function getWorkingHoursAttribute()
    {
        if (!$this->isDentist()) {
            return null;
        }

        return $this->workingHours()->get()->mapWithKeys(function($item) {
            return [
                $item->day_name => [
                    'start' => $item->heure_debut->format('H:i'),
                    'end' => $item->heure_fin->format('H:i')
                ]
            ];
        });
    }

    // Check if user is available at a specific time
    public function isAvailableAt($dateTime)
    {
        if (!$this->isDentist()) {
            return false;
        }

        $carbonDate = \Carbon\Carbon::parse($dateTime);
        $dayOfWeek = strtolower($carbonDate->isoFormat('dddd'));
        $time = $carbonDate->format('H:i:s');

        // Check if within any working hour slot for that day
        return $this->workingHours()
            ->where('jour', $dayOfWeek)
            ->where('heure_debut', '<=', $time)
            ->where('heure_fin', '>=', $time)
            ->exists();
    }

}