<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SiswaImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // Abaikan baris kosong
            if (!$row['nis']) {
                continue;
            }

            // Cek apakah _siswa_ sudah ada
            $siswa = Siswa::where('nis', $row['nis'])->first();

            // Cek user berdasarkan email
            $user = User::where('email', $row['email'])->first();

            if (!$user) {
                $user = User::create([
                    'name'     => $row['nama'],
                    'email'    => $row['email'],
                    'password' => Hash::make($row['nis']),
                    'role'     => 'siswa'
                ]);
            }

            // Insert atau update siswa
            Siswa::updateOrCreate(
                ['nis' => $row['nis']],
                [
                    'user_id' => $user->id,
                    'nama' => $row['nama'],
                    'alamat' => $row['alamat'],
                    'no_hp' => $row['no_hp'],
                    'jenis_kelamin' => $row['jenis_kelamin']
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            '*.nis'   => ['required', 'unique:siswas,nis'],
            '*.nama'  => ['required'],
            '*.email' => ['required', 'email', 'unique:users,email'],
        ];
    }
}
