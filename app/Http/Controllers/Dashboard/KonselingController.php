<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DetailSesi;
use App\Models\Guru;
use App\Models\SesiKonseling;
use App\Models\User;
use App\Notifications\PengajuanKonseling;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class KonselingController extends Controller
{
    public function index()
    {
        $title = 'Konseling';
        $data = SesiKonseling::with('guru', 'siswa');
        if (Auth::user()->role == 'siswa') {
            $data = $data->where('siswa_id', Auth::user()->siswa->id)->get();
        } elseif (Auth::user()->role == 'guru') {
            $data = $data->where('guru_id', Auth::user()->guru->id)->get();
        } else {
            $data = $data->get();
        }
        return view('dashboard.konseling.index', compact('title', 'data'));
    }

    public function create()
    {
        return view('dashboard.konseling.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'topik' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);
        try {
            DB::beginTransaction();
            $randomGuru = Guru::inRandomOrder()->first();
            $waktu = Carbon::parse($request->tanggal);
            $tanggal = $waktu->toDateString();
            $jam     = $waktu->format('H:i');
            $sesi = SesiKonseling::create([
                'siswa_id' => Auth::user()->siswa->id,
                'guru_id' => $randomGuru->id,
                'topik' => $request->topik,
                'deskripsi' => $request->deskripsi,
            ]);
            DetailSesi::create([
                'sesi_id' => $sesi->id,
                'tanggal' => $tanggal,
                'waktu_mulai' => $jam,
            ]);
            $guru = $sesi->guru;
            $user = User::find($guru->user_id);
            Notification::send($user, new PengajuanKonseling($sesi));
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil Mengajukan Konseling, Silahkan Menunggu',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function show(SesiKonseling $konseling)
    {
        $title = 'Konseling';
        return view('dashboard.konseling.show', compact('konseling', 'title'));
    }

    public function edit($id)
    {
        return view('dashboard.konseling.edit', compact('id'));
    }

    public function update(Request $request, SesiKonseling $konseling)
    {
        $request->validate([
            'status' => 'required|in:batal,selesai'
        ]);
        // dd($request->all());
        try {
            DB::beginTransaction();
            $konseling->update([
                'status' => $request->status
            ]);
            DetailSesi::where('sesi_id', $konseling->id)->update([
                'waktu_selesai' => Carbon::now()->format('H:i'),
                'tindak_lanjut' => $request->tindak_lanjut
            ]);
            DB::commit();
            return redirect()->route('konseling.index')->with('success', 'Sesi Konseling Telah dilakukan');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        //
    }
}
