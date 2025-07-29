<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample users
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'created_at' => now()->subDays(30),
                'active' => 1,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'created_at' => now()->subDays(25),
                'active' => 1,
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike@example.com',
                'password' => Hash::make('password'),
                'created_at' => now()->subDays(20),
                'active' => 1,
            ],
            [
                'name' => 'Sarah Wilson',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password'),
                'created_at' => now()->subDays(15),
                'active' => 1,
            ],
        ];
        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
        // Admin user
        User::updateOrCreate([
            'email' => 'admin@company.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('Admin#123'),
            'active' => 1,
        ]);
    }
}
