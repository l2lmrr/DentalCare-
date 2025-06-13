<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    use HasFactory;

    protected $table = 'rendez_vous';

    protected $fillable = [
        'patient_id',
        'dentist_id',
        'date_heure',
        'statut',
        'notes'
    ];

    protected $dates = ['date_heure'];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function dentist()
    {
        return $this->belongsTo(User::class, 'dentist_id')->with('dentist');
    }

    public function dentistInfo()
    {
        return $this->belongsTo(Dentist::class, 'dentist_id', 'user_id');
        return $this->belongsTo(User::class, 'dentist_id');
    }

    // Helper methods
    public function isConfirmed()
    {
        return $this->statut === 'confirmé';
    }

    public function isCancelled()
    {
        return $this->statut === 'annulé';
    }

    public function isRescheduled()
    {
        return $this->statut === 'reporté';
    }

    // Scope for upcoming appointments
    public function scopeUpcoming($query)
    {
        return $query->where('date_heure', '>=', now())
                    ->where('statut', '!=', 'annulé')
                    ->orderBy('date_heure');
    }

    // Check if time slot is available
    public static function isTimeSlotAvailable($dentistId, $dateTime, $excludeAppointmentId = null)
    {
        $query = self::where('dentist_id', $dentistId)
            ->where('date_heure', $dateTime)
            ->where('statut', '!=', 'annulé');

        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return !$query->exists();
    }
}