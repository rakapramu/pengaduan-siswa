<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengaduanController extends Controller
{
    public function index()
    {
        $data = [];
        if (Auth::user()->role == 'siswa') {
            $data = Pengaduan::where('siswa_id', Auth::user()->siswa->id)->get();
        } else {
            $data = Pengaduan::all();
        }
        $title = 'pengaduan';
        return view('dashboard.pengaduan.index', compact('data', 'title'));
    }

    public function create()
    {
        return view('dashboard.pengaduan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'topik' => 'required',
            'deskripsi' => 'required'
        ]);
        try {
            DB::beginTransaction();
            $pengaduan = Pengaduan::create([
                'topik' => $request->topik,
                'deskripsi' => $request->deskripsi,
                'siswa_id' => Auth::user()->siswa->id
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Pengaduan berhasil dikirim',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function show($id)
    {
        return view('dashboard.pengaduan.show', compact('id'));
    }

    public function edit($id)
    {
        return view('dashboard.pengaduan.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'status' => 'required|in:proses,batal,selesai'
        ]);
        $pengaduan->update([
            'status' => $request->status
        ]);
        return back()->with('success', 'Status berhasil diperbarui');
    }


    public function destroy(Pengaduan $pengaduan)
    {
        try {
            $pengaduan->delete();
            return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->route('pengaduan.index')->with('error', 'Pengaduan gagal dihapus');
        }
    }
}
