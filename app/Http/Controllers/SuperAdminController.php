<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SuperAdminController extends Controller
{
    public function index()
    {
        $user = User::all();
        $level_user = DB::table('level_user')->select()->get();
        $user = DB::table('users')
            ->select(['users.nama', 'users.username', 'level_user.nama_level', 'users.foto', 'users.id_user', 'users.level'])
            ->join('level_user', 'users.level', '=', 'level_user.id_level')
            ->orderBy('created_at', 'ASC')
            ->get();
        return view('SuperAdmin.index', compact('user', 'level_user'));
    }

    // tambah
    public function store(Request $request)
    {
        $user = new User;
        // $id_user = substr(md5(rand(0, 99999)), -4);
        $id_user = collect(DB::select('SELECT new_iduser() AS new_iduser'))->first()->new_iduser;
        $user['id_user'] = $id_user;
        $user->nama = $request->input('nama');
        $user->username = $request->input('username');
        $user->password = $request->input('password');
        $user->level = $request->input('id_level');
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('storage/post-images/', $filename);
            $user->foto = $filename;
        }
        $user->save();
        return redirect()->back()->with('success', "Data Berhasil di Tambah");
    }

    // edit
    public function edit($id)
    {
        $user = User::whereIdUser($id)->first();
        return response()->json([
            'user' => $user,
        ]);
    }

    // update
    public function update(Request $request)
    {
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        $user->nama = $request->input('nama');
        $user->username = $request->input('username');
        $user->password = $request->input('password');
        $user->level = $request->input('id_level');
        if ($request->hasFile('foto')) {
            $destination = 'storage/post-images/' . $user->foto;
            if (file::exists($destination)) {
                file::delete($destination);
            }
            $file = $request->file('foto');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('storage/post-images/', $filename);
            $user->foto = $filename;
        }
        $user->update();
        return redirect()->back()->with('success', "Data Berhasil di Edit");
    }

    // hapus
    public function destroy(Request $request)
    {
        $user_id = $request->input('delete_user_id');
        $user = User::find($user_id);
        $destination = 'storage/post-images/' . $user->foto;
        if (file::exists($destination)) {
            file::delete($destination);
        }
        $user->delete();
        return redirect()->back()->with('success', "Data Berhasil di Hapus");
    }

    // search
    public function search(Request $request)
    {
        $get_name = $request->search;
        $user = User::where('nama', 'LIKE', '%' . $get_name, '%')->get();
        return view('SuperAdmin.index', compact('user'));
    }
}
