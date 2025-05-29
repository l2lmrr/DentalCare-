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
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function praticien()
    {
        return $this->belongsTo(User::class, 'praticien_id');
    }
}