<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\UserProvisioningService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(FamilySeeder::class);

        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin'),
                'is_super_admin' => true,
                'person_id' => null,
            ]
        );

        app(UserProvisioningService::class)->syncAllMarriedMales();
    }
}
