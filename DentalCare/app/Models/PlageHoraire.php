<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlageHoraire extends Model
{
    use HasFactory;

    protected $fillable = [
        'praticien_id',
        'jour',
        'heure_debut',
        'heure_fin'
    ];

    public function praticien()
    {
        return $this->belongsTo(Praticien::class);
    }
}