<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    



    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = session('user');

        if (!$user) {
            return redirect()->route('login')
                ->with('warning', 'Для доступа к этому разделу необходимо войти в систему.');
        }

        if ($roles && !in_array($user['role'], $roles, true)) {
            return redirect()->route('products.index')
                ->with('error', 'Доступ запрещён: раздел недоступен для вашей роли (' . $user['role'] . ').');
        }

        return $next($request);
    }
}
