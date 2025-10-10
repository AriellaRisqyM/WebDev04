<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     
        //
        $users =[
        [
            'nama' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ],
        [
            'nama' => 'Dokter',
            'email' => 'Dokter@gmail.com',
            'password' => Hash::make('Dokter123'),
            'role' => 'dokter',
        ],
        [
            'nama' => 'Pasien',
            'email' => 'Pasien@gmail.com',
            'password' => Hash::make('Pasien123'),
            'role' => 'pasien',
        ],
        ];
        foreach ($users as $user){
            User::create($user);
        }
    }
}
