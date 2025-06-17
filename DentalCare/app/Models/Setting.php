<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_name',
        'clinic_address',
        'clinic_phone',
        'clinic_email',
        'business_hours',
        'appointment_interval',
        'logo',
        'favicon',
        'about_text',
        'privacy_policy',
        'terms_conditions'
    ];
}