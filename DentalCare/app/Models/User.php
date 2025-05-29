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

    public function praticien()
    {
        return $this->hasOne(Praticien::class);
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
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
}