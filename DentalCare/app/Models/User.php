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

        public function hasRole($roles)
        {
            if (!is_array($roles)) {
                $roles = [$roles];
            }
            return in_array($this->role, $roles);
        }
    // Patient appointments
    public function patientAppointments()
    {
        return $this->hasMany(RendezVous::class, 'patient_id');
    }

    // Dentist appointments
    public function dentistAppointments()
    {
        return $this->hasMany(RendezVous::class, 'praticien_id');
    }

    // Patient medical records
    public function patientMedicalRecords()
    {
        return $this->hasMany(DossierMedical::class, 'patient_id');
    }

    // Dentist created medical records
    public function dentistMedicalRecords()
    {
        return $this->hasMany(DossierMedical::class, 'praticien_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPraticien()
    {
        return $this->role === 'praticien';
    }

    public function isPatient()
    {
        return $this->role === 'patient';
    }

    public function praticien()
    {
        return $this->hasOne(Praticien::class);
    }
}