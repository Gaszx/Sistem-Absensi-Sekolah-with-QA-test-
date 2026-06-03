<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AdminAcademicYearController extends Controller
{
    // 1. DAFTAR TAHUN AJARAN
    public function index()
    {
        $years = AcademicYear::orderByDesc('created_at')->paginate(10);
        return view('admin.tahun.index', compact('years'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        return view('admin.tahun.create');
    }

    // 3. SIMPAN DATA
    public function store(Request $request)
    {
        \Log::info('STORE TAHUN AJARAN:', $request->all());
        $request->validate([
            'name' => 'required|string', // Contoh: 2025/2026
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        // Logika: Jika user memilih status Aktif, maka tahun lain jadi Tidak Aktif
        $isActive = $request->has('is_active');

        if ($isActive) {
            AcademicYear::query()->update(['is_active' => false]);
        }

        AcademicYear::create([
            'name' => $request->name,
            'semester' => $request->semester,
            'is_active' => $isActive
        ]);

        return redirect()->route('admin.tahun.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    // 4. FORM EDIT
    public function edit($id)
    {
        $year = AcademicYear::findOrFail($id);
        return view('admin.tahun.edit', compact('year'));
    }

    // 5. UPDATE DATA
    public function update(Request $request, $id)
    {
        $year = AcademicYear::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        $isActive = $request->has('is_active');

        // Jika diubah jadi aktif, matikan yang lain
        if ($isActive) {
            AcademicYear::where('id', '!=', $id)->update(['is_active' => false]);
        }

        $year->update([
            'name' => $request->name,
            'semester' => $request->semester,
            'is_active' => $isActive
        ]);

        return redirect()->route('admin.tahun.index')->with('success', 'Tahun ajaran berhasil diupdate.');
    }

    // 6. HAPUS DATA
    public function destroy($id)
    {
        $year = AcademicYear::findOrFail($id);
        
        // Cek apakah tahun ini dipakai oleh Kelas? Kalau iya jangan dihapus sembarangan
        if($year->classrooms()->count() > 0) {
            return back()->with('error', 'Gagal hapus! Masih ada kelas yang menggunakan tahun ajaran ini.');
        }

        $year->delete();
        return redirect()->route('admin.tahun.index')->with('success', 'Tahun ajaran dihapus.');
    }
}