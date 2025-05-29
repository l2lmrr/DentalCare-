<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierMedical extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'praticien_id',
        'diagnostic',
        'traitement',
        'prescription'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function praticien()
    {
        return $this->belongsTo(Praticien::class);
    }
}