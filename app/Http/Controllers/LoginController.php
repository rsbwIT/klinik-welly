<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Helpers\TrackerHelper;

class LoginController extends Controller
{
    public function login()
    {
         return view('loginuser');
    }
    public function mesinLogin(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            TrackerHelper::log('Autentikasi', 'Login', 'Pengguna berhasil masuk ke sistem');
            return redirect()->intended('/')->with('pesanSukses', 'Berhasil Login');
        }
        TrackerHelper::log('Autentikasi', 'Gagal Login', 'Percobaan login gagal dengan email: ' . $request->email);
        Session::flash('error', 'Gagal Login!');
        return redirect('/login');
    }

   // Mesin Logout
   public function mesinLogout(Request $request)
   {
        TrackerHelper::log('Autentikasi', 'Logout', 'Pengguna keluar dari sistem');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
   }

   // add User
   public function addUser()
   {
        $hashid = new Hashids();
        $user = User::orderBy('role', 'asc')->orderBy('id', 'desc')->get();
        return view('formadduser', ['user'=>$user, 'hashid'=>$hashid]);
   }
   public function addUserKlinik(Request $request)
   {
        $request->validate([
            'email'=>'required|email|unique:users',
            'name'=>'required',
            'no_telpon'=>'required',
            'password'=>'required',
        ]);
        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'no_telpon' => $request->no_telpon,
            'password' => Hash::make($request->password),
            'raw_password' => $request->password,
            'role' => $request->role,
            'alamat' => $request->alamat
        ]);

        TrackerHelper::log('Manajemen Pengguna', 'Tambah Akun', "Menambahkan akun baru: {$user->name} ({$user->email}) - Role: {$user->role}");

        return redirect('addUser')->with('succsessAddUser','Berhasil Menambahkan Petugas');
   }
    //  Edit User
    public function editPetugas($id)
    {
        $hashid = new Hashids();
        $user = User::find($hashid->decodeHex($id));
        if (User::where('id', $hashid->decodeHex($id))->exists()){
            return view('formeditpetugas', ['user'=>$user, 'hashid'=>$hashid]);
        }else{
            abort(404);
        }
    }
    public function editUserKlinik(Request $request, $id)
    {
        $hashid = new Hashids();
        $editUser = User::find($hashid->decodeHex($id));
        $editUser->email = $request->email;
        $editUser->name = $request->name;
        $editUser->no_telpon = $request->no_telpon;
        if (!empty($request->password)) {
            $editUser->password = Hash::make($request->password);
            $editUser->raw_password = $request->password;
        }
        $editUser->role = $request->role;
        $editUser->alamat = $request->alamat;
        $editUser->save();

        TrackerHelper::log('Manajemen Pengguna', 'Ubah Akun', "Mengubah data akun: {$editUser->name} ({$editUser->email}) - Role: {$editUser->role}");

        return redirect('addUser')->with('editSuccess','Data Berhasil Merubah Data, '.$request->email.'!!');
    }

    // hapuPetugas
    public function hapuPetugas($id)
    {
        $hashid = new Hashids();
        $targetUser = User::find($hashid->decodeHex($id));
        if ($targetUser) {
            TrackerHelper::log('Manajemen Pengguna', 'Hapus Akun', "Menghapus akun: {$targetUser->name} ({$targetUser->email})");
            $targetUser->delete();
        }
        return redirect('addUser')->with('succsessDeleteUser','Berhasil Menghapus Data Petugas');
    }
}
