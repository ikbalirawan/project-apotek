<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
     // karena function diakses setelah form di submit, jadi perlu parameter request
     public function authlogin(Request $request)
     {
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        // simpan data dari inputan email dan password ke dalam variable untuk memudahkan pemanggilannya
        $user = $request->only(['email', 'password']);
        // attempt : mengecek kococokan email dan password kemudian meyimpannya ke dalama class Auth(memberi indetitas data riwayat login ke projectnya)
        if (Auth::attempt($user)) {
            // perbedaan redirect() dan redirect() -> route ?? redirect() -> path /, route() -> name
            return redirect('/dashboard');
        }else{
            return redirect()->back()->with('failed', 'Login gagal silahkan coba lagi');
        }
     }

     public function logout()
     {
        // menghapus / menghilangkan data session login
        Auth::logout();
        return redirect()->route('login');
     }

    public function index()
    {
        $users = User::orderBy('name', 'ASC')->simplePaginate(5);
        return view('account.user', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('account.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,',
            'role' => 'required',
        ]);

        $email = substr($request->email, 0, 3);
        $name = substr($request->name, 0, 3);
        $hashedCreate = Hash::make($email . $name);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => $request->$hashedCreate
        ]);
        
        return redirect()->back()->with('success', 'Berhasil menambahkan data user!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('account.edit', compact('user'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required',
        ]);
        
        $hashed = Hash::make($request->password);
        User::where('id', $id)->update([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'password' => $hashed 
    ]);

        return redirect()->route('account.user')->with('success', 'Berhasil mengubah data user!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::where('id', $id)->delete();
        return redirect()->back()->with('deleted', 'Berhasil menghapus data user!');
    }
}
