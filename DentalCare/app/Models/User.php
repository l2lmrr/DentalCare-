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
        'working_hours' => 'array' // If you want to access it directly from User
    ];

    public function hasRole($roles)
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        return in_array($this->role, $roles);
    }

    // Dentist relationship
    public function dentist()
    {
        return $this->hasOne(Dentist::class);
    }

    // Patient appointments
    public function patientAppointments()
    {
        return $this->hasMany(RendezVous::class, 'patient_id');
    }

    // Dentist appointments
    public function dentistAppointments()
    {
        return $this->hasMany(RendezVous::class, 'dentist_id');
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

    // Accessor for photo
    public function getPhotoUrlAttribute()
    {
        return $this->dentist && $this->dentist->photo 
            ? asset('storage/'.$this->dentist->photo) 
            : asset('images/default-dentist.jpg');
    }
}