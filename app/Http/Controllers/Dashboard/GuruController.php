<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index()
    {
        $data = User::with('guru')->where('role', 'guru')->get();
        $title = 'Guru';
        return view('dashboard.guru.index', compact('data', 'title'));
    }

    public function create()
    {
        $title = 'Guru';
        return view('dashboard.guru.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'nip' => 'required|unique:gurus,nip',
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
                'role' => 'guru',
            ]);
            $guru = Guru::create([
                'user_id' => $user->id,
                'nama' => $request->name,
                'nip' => $request->nip,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp
            ]);

            DB::commit();
            return redirect()->route('guru.index')->with('success', 'Guru berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function show($id)
    {
        return view('dashboard.guru.show', compact('id'));
    }

    public function edit(User $guru)
    {
        $title = 'Guru';
        return view('dashboard.guru.edit', compact('guru', 'title'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable',
            'nip' => 'required',
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

            $user->role = 'guru';
            $user->save();

            $guru = Guru::where('user_id', $id)->firstOrFail();
            $guru->nama = $request->name;
            $guru->nip = $request->nip;
            $guru->alamat = $request->alamat;
            $guru->no_hp = $request->no_hp;
            $guru->jenis_kelamin = $request->jenis_kelamin;
            $guru->save();

            DB::commit();
            return redirect()->route('guru.index')->with('success', 'Guru berhasil diupdate');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Guru::where('user_id', $id)->delete();
            User::where('id', $id)->delete();
            return redirect()->route('guru.index')->with('success', 'Guru berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
