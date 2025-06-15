<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierMedical extends Model
{
    use HasFactory;

    protected $table = 'dossiers_medicaux';

    protected $fillable = [
        'patient_id',
        'dentist_id',
        'diagnostic',
        'traitement',
        'prescription',
        'rendez_vous_id'
    ];

    protected $with = ['patient', 'dentist'];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function dentist()
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }

    public function rendez_vous()
    {
        return $this->belongsTo(RendezVous::class, 'rendez_vous_id');
    }
}