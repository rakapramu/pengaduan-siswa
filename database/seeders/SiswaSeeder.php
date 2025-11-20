<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 30; $i++) {

            // Generate data
            $nis = $faker->numerify('##########'); // 10 digit
            $nama = $faker->name;
            $email = strtolower(str_replace(' ', '', $nama)) . rand(10, 99) . '@mail.com';

            // Create User
            $user = User::create([
                'name' => $nama,
                'email' => $email,
                'password' => Hash::make($nis), // password = nis
                'role' => 'siswa',
            ]);

            // Create Siswa
            Siswa::create([
                'user_id' => $user->id,
                'nama' => $nama,
                'nis' => $nis,
                'alamat' => $faker->address,
                'no_hp' => $faker->numerify('08##########'),
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
            ]);
        }
    }
}
