<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Praticien extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialite',
        'bio'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plagesHoraires()
    {
        return $this->hasMany(PlageHoraire::class);
    }

    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }

    public function dossiersMedicaux()
    {
        return $this->hasMany(DossierMedical::class);
    }
}