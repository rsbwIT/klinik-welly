<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Helpers\TrackerHelper;
use Hashids\Hashids;

class TrackerController extends Controller
{
    private $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids();
    }

    /**
     * Tampilkan Halaman Log Aktivitas / Tracker Sistem
     */
    public function index(Request $request)
    {
        $query = DB::table('activity_trackers')->orderBy('id', 'desc');

        if ($request->filled('user_email')) {
            $query->where('user_email', $request->user_email);
        }

        if ($request->filled('modul')) {
            $query->where('modul', $request->modul);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $logs = $query->paginate(25);

        // Ambil list user dan modul unik untuk filter
        $users = User::select('id', 'name', 'email', 'role')->get();
        $moduls = DB::table('activity_trackers')->select('modul')->distinct()->pluck('modul');

        return view('tracker.index', [
            'logs' => $logs,
            'users' => $users,
            'moduls' => $moduls
        ]);
    }

    /**
     * Tampilkan Halaman Pengaturan Hak Akses User
     */
    public function permissions($id)
    {
        $realId = $this->hashids->decodeHex($id);
        if (!$realId && is_numeric($id)) {
            $realId = $id;
        }

        $user = User::find($realId);
        if (!$user) {
            abort(404, 'Data pengguna tidak ditemukan');
        }

        // Ambil atau buat record izin
        $permissions = DB::table('user_permissions')->where('user_id', $user->id)->first();
        if (!$permissions) {
            DB::table('user_permissions')->insert([
                'user_id' => $user->id,
                'menu_master_pasien' => 1,
                'menu_registrasi' => 1,
                'menu_soap' => 1,
                'menu_lab' => 1,
                'menu_padepokan' => 1,
                'hapus_pasien' => $user->role == 'admin' ? 1 : 0,
                'hapus_soap' => $user->role == 'admin' ? 1 : 0,
                'kelola_user' => $user->role == 'admin' ? 1 : 0,
                'lihat_tracker' => $user->role == 'admin' ? 1 : 0,
            ]);
            $permissions = DB::table('user_permissions')->where('user_id', $user->id)->first();
        }

        return view('tracker.permissions', [
            'user' => $user,
            'permissions' => $permissions,
            'hashId' => $id
        ]);
    }

    /**
     * Simpan Pengaturan Hak Akses User
     */
    public function updatePermissions(Request $request, $id)
    {
        $realId = $this->hashids->decodeHex($id);
        if (!$realId && is_numeric($id)) {
            $realId = $id;
        }

        $user = User::find($realId);
        if (!$user) {
            abort(404, 'Data pengguna tidak ditemukan');
        }

        DB::table('user_permissions')
            ->where('user_id', $user->id)
            ->update([
                'menu_master_pasien' => $request->has('menu_master_pasien') ? 1 : 0,
                'menu_registrasi' => $request->has('menu_registrasi') ? 1 : 0,
                'menu_soap' => $request->has('menu_soap') ? 1 : 0,
                'menu_lab' => $request->has('menu_lab') ? 1 : 0,
                'menu_padepokan' => $request->has('menu_padepokan') ? 1 : 0,
                'hapus_pasien' => $request->has('hapus_pasien') ? 1 : 0,
                'hapus_soap' => $request->has('hapus_soap') ? 1 : 0,
                'kelola_user' => $request->has('kelola_user') ? 1 : 0,
                'lihat_tracker' => $request->has('lihat_tracker') ? 1 : 0,
            ]);

        TrackerHelper::log('Hak Akses', 'Update', "Admin mengubah hak akses untuk pengguna: {$user->name} ({$user->email})");

        return redirect()->back()->with('success', "Hak akses untuk {$user->name} berhasil diperbarui!");
    }
}

