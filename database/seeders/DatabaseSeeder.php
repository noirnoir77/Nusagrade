<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Idempotent admin user seeder driven by .env vars so production can
     * bootstrap an admin without tinker/SSH. Avoids the factory because
     * Faker is a dev-only dependency.
     */
    public function run(): void
    {
        $email    = env('ADMIN_EMAIL');
        $name     = env('ADMIN_NAME', 'Admin');
        $password = env('ADMIN_PASSWORD');

        if (empty($email) || empty($password)) {
            $this->command?->warn('Skipping admin seeding: ADMIN_EMAIL and ADMIN_PASSWORD must be set in .env');

            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name'              => $name,
                'password'          => Hash::make($password),
                'is_admin'          => true,
                'email_verified_at' => now(),
            ],
        );

        $this->command?->info("Admin user ensured for {$email}");
    }
}
