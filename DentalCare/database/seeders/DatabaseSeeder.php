<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Dentist;
use App\Models\PlageHoraire;
use App\Models\RendezVous;
use App\Models\DossierMedical;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Truncate all tables
        DossierMedical::truncate();
        RendezVous::truncate();
        PlageHoraire::truncate();
        Dentist::truncate();
        User::truncate();

        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@dentalcare.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        // Create dentists
        $dentists = [];
        for ($i = 1; $i <= 3; $i++) {
            $dentist = User::create([
                'name' => "Dr. Dentist {$i}",
                'email' => "dentist{$i}@dentalcare.com",
                'password' => Hash::make('password123'),
                'role' => 'dentist'
            ]);

            Dentist::create([
                'user_id' => $dentist->id,
                'license_number' => "LIC{$i}2025",
                'years_of_experience' => rand(2, 15),
                'bio' => "Experienced dentist specializing in general dentistry with {$i} years of experience."
            ]);

            $dentists[] = $dentist;
        }

        // Create patients
        $patients = [];
        for ($i = 1; $i <= 10; $i++) {
            $patient = User::create([
                'name' => "Patient {$i}",
                'email' => "patient{$i}@example.com",
                'password' => Hash::make('password123'),
                'role' => 'patient'
            ]);
            $patients[] = $patient;
        }

        // Create working hours for dentists
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        foreach ($dentists as $dentist) {
            foreach ($days as $day) {
                PlageHoraire::create([
                    'dentist_id' => $dentist->dentist->id,
                    'jour' => $day,
                    'heure_debut' => '09:00',
                    'heure_fin' => '17:00'
                ]);
            }
        }

        // Create past and future appointments with medical records
        foreach ($patients as $patient) {
            foreach ($dentists as $dentist) {
                // Create past appointments with medical records
                for ($i = 0; $i < 2; $i++) {
                    $date = Carbon::now()->subDays(rand(1, 30))->setHour(rand(9, 16))->setMinute(0);
                    
                    $appointment = RendezVous::create([
                        'patient_id' => $patient->id,
                        'dentist_id' => $dentist->id,
                        'date_heure' => $date,
                        'statut' => 'confirmé', // Using a valid status from the enum
                        'notes' => 'Completed checkup and cleaning appointment'
                    ]);

                    // Create medical record with prescription for past appointments
                    $conditions = ['Cavity', 'Gingivitis', 'Root Canal', 'Tooth Decay', 'Regular Checkup'];
                    $medications = [
                        'Amoxicillin 500mg - Take 1 tablet 3 times per day for 7 days',
                        'Ibuprofen 400mg - Take as needed for pain, maximum 4 times per day',
                        'Chlorhexidine Mouthwash - Rinse twice daily for 30 seconds',
                        'Fluoride Treatment - Apply as directed for 1 week',
                    ];

                    DossierMedical::create([
                        'patient_id' => $patient->id,
                        'dentist_id' => $dentist->id,
                        'diagnostic' => $conditions[array_rand($conditions)],
                        'traitement' => 'Treatment plan includes cleaning, filling, and medication',
                        'prescription' => $medications[array_rand($medications)],
                    ]);
                }

                // Create some future appointments
                for ($i = 0; $i < 2; $i++) {
                    $date = Carbon::now()->addDays(rand(1, 30))->setHour(rand(9, 16))->setMinute(0);
                    
                    RendezVous::create([
                        'patient_id' => $patient->id,
                        'dentist_id' => $dentist->id,
                        'date_heure' => $date,
                        'statut' => 'confirmé',
                        'notes' => 'Regular checkup and cleaning appointment'
                    ]);
                    // We don't create medical records for future appointments
                }
            }
        }

        // Settings table has no columns to seed
    }
}
