<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
public function login(Request $request)
{
    $request->validate([
        'nome' => 'required|string',
        'senha' => 'required|string',
    ]);

    $usuario = Usuario::where('nome', $request->nome)->first();

    if (!$usuario || $request->senha !== $usuario->senha) {
        throw ValidationException::withMessages([
            'nome' => ['As credenciais fornecidas estão incorretas.'],
        ]);
    }

    Auth::guard('usuarios')->login($usuario);
    $request->session()->regenerate();

    return redirect()->intended('/');
}
public function logout(Request $request)
{
    Auth::guard('usuarios')->logout(); // ou Auth::logout() dependendo do guard usado

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
}
}

