<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // окно входа — первое, что видит пользователь
    public function showLogin()
    {
        if (session()->has('user')) {
            return redirect()->route('products.index');
        }

        return view('login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required'    => 'Введите логин.',
            'password.required' => 'Введите пароль.',
        ]);

        $user = User::with('role')->where('login', $data['login'])->first();

        if (!$user || $user->password !== $data['password']) {
            return back()
                ->withInput($request->only('login'))
                ->with('error', 'Неверный логин или пароль. Проверьте правильность ввода данных и повторите попытку.');
        }

        session([
            'user' => [
                'id'        => $user->id,
                'full_name' => $user->full_name,
                'role'      => $user->role->name,
            ],
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Добро пожаловать, ' . $user->full_name . '!');
    }

    // вход в роли гостя — без авторизации
    public function guest()
    {
        session()->forget('user');

        return redirect()->route('products.index');
    }

    // выход на главный экран — окно входа
    public function logout()
    {
        session()->forget('user');

        return redirect()->route('login');
    }
}
