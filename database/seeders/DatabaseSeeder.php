<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;

use App\Models\Role;
use App\Models\User;
use App\Models\Guru;
use App\Models\Jabatan;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\Indikator;
use App\Models\IndikatorScore;

use App\Models\Period;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
        =====================================================
        ROLE
        =====================================================
        */

        $adminRole = Role::create([
            'name' => 'admin'
        ]);

        $guruRole = Role::create([
            'name' => 'guru'
        ]);

        $penilaiRole = Role::create([
            'name' => 'penilai'
        ]);

        /*
        =====================================================
        JABATAN
        =====================================================
        */

        $guruTetap = Jabatan::create([
            'name' => 'Guru Tetap'
        ]);

        $guruHonorer = Jabatan::create([
            'name' => 'Guru Honorer'
        ]);

        /*
        =====================================================
        ADMIN
        =====================================================
        */

        User::create([

            'name' => 'Administrator',

            'email' => 'admin@mail.com',

            'password' => Hash::make('password'),

            'role_id' => $adminRole->id,
        ]);

        /*
        =====================================================
        PENILAI
        =====================================================
        */

        User::create([

            'name' => 'Penilai 1',

            'email' => 'penilai@mail.com',

            'password' => Hash::make('password'),

            'role_id' => $penilaiRole->id,
        ]);

        /*
        =====================================================
        GURU
        =====================================================
        */

        $subjects = [

            'Matematika',

            'Bahasa Indonesia',

            'Bahasa Inggris',

            'IPA',

            'IPS',
        ];

        foreach ($subjects as $index => $subject) {

            $user = User::create([

                'name' =>
                'Guru ' . ($index + 1),

                'email' =>
                'guru' . ($index + 1) . '@mail.com',

                'password' =>
                Hash::make('password'),

                'role_id' =>
                $guruRole->id,
            ]);

            Guru::create([

                'user_id' =>
                $user->id,

                'jabatan_id' =>
                $index % 2 == 0
                    ? $guruTetap->id
                    : $guruHonorer->id,

                'nip' =>
                '19870' . rand(10000, 99999),

                'subject' =>
                $subject,

                'phone' =>
                '0812' . rand(1000000, 9999999),

                'address' =>
                'Jl. Pendidikan No. ' . rand(1, 100),
            ]);
        }

        /*
        =====================================================
        PERIODE
        =====================================================
        */

        Period::create([

            'name' =>
            'Semester Ganjil 2025',

            'start_date' =>
            '2025-07-01',

            'end_date' =>
            '2025-12-31',

            'is_active' => true,

            'is_locked' => false,
        ]);

        Period::create([

            'name' =>
            'Semester Genap 2026',

            'start_date' =>
            '2026-01-01',

            'end_date' =>
            '2026-06-30',

            'is_active' => false,

            'is_locked' => false,
        ]);

        /*
        =====================================================
        KRITERIA
        =====================================================
        */

        $pedagogik = Kriteria::create([

            'name' =>
            'Kompetensi Pedagogik',


            'bobot' => 40,
        ]);

        $profesional = Kriteria::create([

            'name' =>
            'Kompetensi Profesional',



            'bobot' => 30,
        ]);

        $kepribadian = Kriteria::create([

            'name' =>
            'Kompetensi Kepribadian',


            'bobot' => 20,
        ]);

        $sosial = Kriteria::create([

            'name' =>
            'Kompetensi Sosial',


            'bobot' => 10,
        ]);

        /*
        =====================================================
        SUB KRITERIA
        =====================================================
        */

        $subPedagogik1 = SubKriteria::create([

            'kriteria_id' =>
            $pedagogik->id,

            'kode' => 'A1',

            'name' =>
            'Memahami Karakteristik Peserta Didik',

            'bobot' => 20,
        ]);

        $subPedagogik2 = SubKriteria::create([

            'kriteria_id' =>
            $pedagogik->id,

            'kode' => 'A2',

            'name' =>
            'Perencanaan Pembelajaran',

            'bobot' => 20,
        ]);

        $subProfesional = SubKriteria::create([

            'kriteria_id' =>
            $profesional->id,

            'kode' => 'B1',

            'name' =>
            'Penguasaan Materi Pembelajaran',

            'bobot' => 15,
        ]);

        $subKepribadian = SubKriteria::create([

            'kriteria_id' =>
            $kepribadian->id,

            'kode' => 'C1',

            'name' =>
            'Etika dan Disiplin Guru',

            'bobot' => 10,
        ]);

        $subSosial = SubKriteria::create([

            'kriteria_id' =>
            $sosial->id,

            'kode' => 'D1',

            'name' =>
            'Komunikasi Sosial',

            'bobot' => 10,
        ]);

        /*
        =====================================================
        INDIKATOR
        =====================================================
        */

        $indikatorList = [

            /*
    =====================================================
    PEDAGOGIK
    =====================================================
    */

            [
                'sub_kriteria_id' =>
                $subPedagogik1->id,

                'name' =>
                'Guru memahami karakteristik peserta didik'
            ],

            [
                'sub_kriteria_id' =>
                $subPedagogik1->id,

                'name' =>
                'Guru memperhatikan kebutuhan belajar siswa'
            ],

            [
                'sub_kriteria_id' =>
                $subPedagogik2->id,

                'name' =>
                'Guru menyusun RPP dengan baik'
            ],

            [
                'sub_kriteria_id' =>
                $subPedagogik2->id,

                'name' =>
                'Guru menyusun tujuan pembelajaran'
            ],

            /*
    =====================================================
    PROFESIONAL
    =====================================================
    */

            [
                'sub_kriteria_id' =>
                $subProfesional->id,

                'name' =>
                'Guru menguasai materi pelajaran'
            ],

            /*
    =====================================================
    KEPRIBADIAN
    =====================================================
    */

            [
                'sub_kriteria_id' =>
                $subKepribadian->id,

                'name' =>
                'Guru disiplin dalam melaksanakan tugas'
            ],

            /*
    =====================================================
    SOSIAL
    =====================================================
    */

            [
                'sub_kriteria_id' =>
                $subSosial->id,

                'name' =>
                'Guru berkomunikasi baik dengan siswa'
            ],

        ];

        foreach ($indikatorList as $item) {

            $indikator = Indikator::create([

                'sub_kriteria_id' =>
                $item['sub_kriteria_id'],

                'name' =>
                $item['name'],

            ]);

            /*
    =====================================================
    RUBRIK SKOR
    =====================================================
    */

            IndikatorScore::create([

                'indikator_id' =>
                $indikator->id,

                'score' => 1,

                'description' =>
                'Sangat Kurang',
            ]);

            IndikatorScore::create([

                'indikator_id' =>
                $indikator->id,

                'score' => 2,

                'description' =>
                'Kurang',
            ]);

            IndikatorScore::create([

                'indikator_id' =>
                $indikator->id,

                'score' => 3,

                'description' =>
                'Baik',
            ]);

            IndikatorScore::create([

                'indikator_id' =>
                $indikator->id,

                'score' => 4,

                'description' =>
                'Sangat Baik',
            ]);
        }
    }
}
