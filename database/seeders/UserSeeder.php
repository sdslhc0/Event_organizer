<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        // Akun Admin & User Demo utama
        User::firstOrCreate(
            ['email' => 'admin@melodi.com'],
            [
                'nama' => 'Admin MELODI',
                'telepon' => '08123456789',
                'password' => $password,
                'role' => '1',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@melodi.com'],
            [
                'nama' => 'User Demo',
                'telepon' => '08987654321',
                'password' => $password,
                'role' => '0',
            ]
        );

        // Akun pelanggan dummy untuk riwayat booking & responden kuisioner SPK
        $customers = [
            ['nama' => 'Amanda Putri',   'email' => 'amanda.putri@gmail.com',   'telepon' => '081234567801'],
            ['nama' => 'Rizky Ramadhan', 'email' => 'rizky.ramadhan@gmail.com', 'telepon' => '081234567802'],
            ['nama' => 'Dimas Pratama',  'email' => 'dimas.pratama@gmail.com',  'telepon' => '081234567803'],
            ['nama' => 'Bella Shofie',   'email' => 'bella.shofie@gmail.com',   'telepon' => '081234567804'],
            ['nama' => 'Kevin Sanjaya',  'email' => 'kevin.sanjaya@gmail.com',  'telepon' => '081234567805'],
            ['nama' => 'Citra Kirana',   'email' => 'citra.kirana@gmail.com',   'telepon' => '081234567806'],
            ['nama' => 'Farhan Maulana', 'email' => 'farhan.m@gmail.com',       'telepon' => '081234567807'],
            ['nama' => 'Tiara Andini',   'email' => 'tiara.andini@gmail.com',   'telepon' => '081234567808'],
            ['nama' => 'Aditya Nugraha', 'email' => 'aditya.nugraha@gmail.com', 'telepon' => '081234567809'],
            ['nama' => 'Maya Anggraini', 'email' => 'maya.anggraini@gmail.com', 'telepon' => '081234567810'],
        ];

        foreach ($customers as $c) {
            User::firstOrCreate(
                ['email' => $c['email']],
                [
                    'nama' => $c['nama'],
                    'telepon' => $c['telepon'],
                    'password' => $password,
                    'role' => '0',
                ]
            );
        }
    }
}
