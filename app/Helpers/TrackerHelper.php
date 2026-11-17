<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class TrackerHelper
{
    /**
     * Catat log aktivitas user ke tabel activity_trackers
     */
    public static function log($modul, $action, $keterangan = null)
    {
        try {
            $user = Auth::user();
            DB::table('activity_trackers')->insert([
                'user_id' => $user ? $user->id : null,
                'user_name' => $user ? $user->name : 'Guest',
                'user_email' => $user ? $user->email : '-',
                'user_role' => $user ? $user->role : '-',
                'modul' => $modul,
                'action' => $action,
                'keterangan' => $keterangan,
                'ip_address' => Request::ip(),
                'user_agent' => Request::header('User-Agent'),
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Fail gracefully agar tidak mengganggu flow utama
        }
    }

    /**
     * Cek apakah user saat ini memiliki izin hak akses tertentu
     */
    public static function can($permission)
    {
        $user = Auth::user();
        if (!$user) return false;

        // Admin selalu memiliki akses penuh
        if ($user->role === 'admin') return true;

        $perm = DB::table('user_permissions')->where('user_id', $user->id)->first();
        if (!$perm) return true; // Default allow jika belum diset khusus

        return isset($perm->$permission) && $perm->$permission == 1;
    }
}

