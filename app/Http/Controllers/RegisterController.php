<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index() 
    {
        return view('auth.register');
    }

    public function store(Request $request) 
    {
        // Modificar el Request
        $request->request->add(['username' => Str::slug( $request->username )]);

        // Validacion
        $this->validate($request,[
            'name' => 'required | min:3 | max:30',
            'username' => 'required | min:3 | max:20 | unique:users',
            'email' => 'required | max:60 |unique:users| email',
            'password' => 'required | min:6 | confirmed',
        ]);

        // Creacion de registros
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make( $request->password )
        ]);

        // Autenticar un usuario
        auth()->attempt($request->only('email', 'password'));


        // Redireccionar al usuario
        return redirect()->route('posts.index');

    }
}