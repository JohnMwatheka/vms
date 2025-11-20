<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Initiator',     'email' => 'initiator@test.com',   'role' => 'initiator'],
            ['name' => 'Vendor User',   'email' => 'vendor@test.com',      'role' => 'vendor'],
            ['name' => 'Checker',       'email' => 'checker@test.com',     'role' => 'checker'],
            ['name' => 'Procurement',   'email' => 'procurement@test.com', 'role' => 'procurement'],
            ['name' => 'Legal',         'email' => 'legal@test.com',       'role' => 'legal'],
            ['name' => 'Finance',       'email' => 'finance@test.com',     'role' => 'finance'],
            ['name' => 'Directors',     'email' => 'directors@test.com',   'role' => 'directors'],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('password'),
                ]
            )->assignRole($u['role']);
        }
    }
}