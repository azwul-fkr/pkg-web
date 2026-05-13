<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Guru;
use App\Models\Jabatan;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // ambil role
        $adminRole = Role::where('name', 'admin')->first();
        $guruRole = Role::where('name', 'guru')->first();
        $penilaiRole = Role::where('name', 'penilai')->first();

        // buat jabatan dulu
        $jabatan = Jabatan::create([
            'name' => 'Guru Tetap'
        ]);

        // =====================
        // ADMIN
        // =====================
        User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id
        ]);

        // =====================
        // GURU 1
        // =====================
        $guruUser1 = User::create([
            'name' => 'Guru 1',
            'email' => 'guru1@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $guruRole->id
        ]);

        Guru::create([
            'user_id' => $guruUser1->id,
            'jabatan_id' => $jabatan->id
        ]);

        // =====================
        // GURU 2
        // =====================
        $guruUser2 = User::create([
            'name' => 'Guru 2',
            'email' => 'guru2@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $guruRole->id
        ]);

        Guru::create([
            'user_id' => $guruUser1->id,
            'jabatan_id' => $jabatan->id,

            'nip' => '198812121',

            'subject' => 'Matematika',

            'phone' => '08123456789',

            'address' => 'Bandung'
        ]);

        // =====================
        // PENILAI
        // =====================
        User::create([
            'name' => 'Penilai',
            'email' => 'penilai@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $penilaiRole->id
        ]);
    }
}
