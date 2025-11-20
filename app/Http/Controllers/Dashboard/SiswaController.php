<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Imports\SiswaImport;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index()
    {
        $data = User::with('siswa')->where('role', 'siswa')->get();
        $title = 'Siswa';
        return view('dashboard.siswa.index', compact('data', 'title'));
    }

    public function create()
    {
        $title = 'Siswa';
        return view('dashboard.siswa.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'nis' => 'required|unique:siswas,nis',
            'alamat' => 'required',
            'no_hp' => 'required|numeric',
            'jenis_kelamin' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'siswa',
            ]);
            $siswa = Siswa::create([
                'user_id' => $user->id,
                'nama' => $request->name,
                'nis' => $request->nis,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp
            ]);

            DB::commit();
            return redirect()->route('siswa.index')->with('success', 'Guru berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function show(User $siswa)
    {
        $title = 'Siswa';
        return view('dashboard.siswa.show', compact('siswa', 'title'));
    }

    public function edit(User $siswa)
    {
        $title = 'Siswa';
        return view('dashboard.siswa.edit', compact('siswa', 'title'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable',
            'nis' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required|numeric',
            'jenis_kelamin' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->role = 'siswa';
            $user->save();

            $guru = Siswa::where('user_id', $id)->firstOrFail();
            $guru->nama = $request->name;
            $guru->nis = $request->nis;
            $guru->alamat = $request->alamat;
            $guru->no_hp = $request->no_hp;
            $guru->jenis_kelamin = $request->jenis_kelamin;
            $guru->save();

            DB::commit();
            return redirect()->route('siswa.index')->with('success', 'Guru berhasil diupdate');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Siswa::where('user_id', $id)->delete();
            User::where('id', $id)->delete();
            return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $import = new SiswaImport();

        Excel::import($import, $request->file('file'));

        // Jika ada baris gagal
        if ($import->failures()->isNotEmpty()) {
            return back()->with([
                'failures' => $import->failures()
            ]);
        }

        return back()->with('success', 'Data siswa berhasil diimport!');
    }
}
