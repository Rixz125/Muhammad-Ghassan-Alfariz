<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;

class UserController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'first_name'    => 'required|min:3',
            'last_name'     => 'required|min:3',
            'email'         => 'required|email:dns',
            'password'      => 'required|min:8'
        ],[
            'first_name.required' => 'Nama depan harus diisi.',
            'first_name.min'      => 'Nama depan harus terdiri dari minimal 3 karakter.',
            'last_name.required'  => 'Nama belakang harus diisi.',
            'last_name.min'       => 'Nama belakang harus terdiri dari minimal 3 karakter.',
            'email.required'      => 'Email harus diisi.',
            'email.email'         => 'Format email tidak valid.',
            'password.required'   => 'Password harus diisi.',
            'password.min'        => 'Password harus terdiri dari minimal 8 karakter.',
        ]);

        $createData = User::create([
            'name'     => $request->first_name . ' ' . $request->last_name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user'
        ]);

        if ($createData) {
            return redirect()->route('login')->with('success','Berhasil membuat akun, silahkan login');
        }else{
            return redirect()->route('signup')->with('failed', 'Gagal memproses data!, silahkan coba lagi');

        }

    }

    public function authentication(request $request)
{
    $request->validate([
        'email' => 'required',
        'password' => 'required',
    ],[
        'email.required' => 'email harus diisi',
        'password.required' => 'password harus diisi',
    ]);

    $data = $request->only(['email', 'password']);

    $user = User::where('email', $request->email)->first();
if (!$user) {
    return redirect()->back()->with('error', 'Email tidak ditemukan');
}
if (!Hash::check($request->password, $user->password)) {
    return redirect()->back()->with('error', 'Password salah');
}


    if (Auth::attempt($data))

    if (Auth::user()->role == 'admin'){
        return redirect()->route('admin.dashboard')->with('success',
        'Berhasil login!');

    }elseif (Auth::user()->role == 'staff'){
        return redirect()->route('staff.dashboard')->with('success',
        'Berhasil login!');

    }else {return redirect()->route('home')->with('success', 'berhasil login!');

    }else{
        return redirect()->back()->with('error','gagal! pastikan email dan password benar');
    }
}

public function logout()
{
    Auth::logout();
    return redirect()->route('home')->with('logout', 'Anda telah logout!
    Silahkan login kembali untuk akses lengkap');
}

  public function index()
    {
        $users = User::whereIn('role', ['admin', 'staff'])->get();
        return view('admin.staff.istaff', compact('users'));
    }

    public function create()
    {
        return view('admin.staff.cstaff');
    }

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email', // Tambahkan validasi unik
        'password' => 'required'
    ], [
        'name.required' => 'Nama lengkap anda harus diisi',
        'email.required' => 'Email harus diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah digunakan', // Tambahkan pesan error
        'password.required' => 'Password harus diisi'
    ]);

    $createData = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'staff'
    ]);

    if ($createData) {
        return redirect()->route('admin.users.istaf')->with('success', 'Berhasil Menambah Data');
    } else {
        return redirect()->back()->with('error', 'Gagal! silahkan coba lagi.');
    }
}



    public function show(string $id)
    {

    }

    public function edit($id)
    {
        $users = User::find($id);
        return view('admin.staff.estaff', compact('users'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required'
        ], [
            'name.required' => 'Nama lengkap anda harus diisi',
            'email.required' => 'Email harus diisi'
        ]);
        $updateData = User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        if ($updateData) {
            return redirect()->route('admin.users.istaf')->with('success', 'Berhasil mengubah data');
        } else {
            return redirect()->back()->with('error', 'Gagal! silahkan coba lagi.');
        }
    }

    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return redirect()->route('admin.users.istaf')->with('success', 'Berhasil hapus data');
    }
// method export
    public function export()
  {
    // nama file yang akan di download
    // ekstensi 
    $fileName= "data-film.xlsx";
    return Excel::download(new UserExport, $fileName);
  }

  public function trash()
    {
        $usersTrash = User::onlyTrashed()->get();
        return view('admin.staff.trash', compact('usersTrash'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->where('id', $id)->first();
        $user->restore();
        return redirect()->route('admin.users.trash')->with('success', 'Berhasil mengembalikan data');
    }

    public function deletePermanent($id)
    {
        $user = User::onlyTrashed()->find($id);
        $user->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data secara permanen');
    }
}
