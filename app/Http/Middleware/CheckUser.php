<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
 public function handle(Request $request, Closure $next)
    {
        $id = session()->get('id');

        if (!$id) {
            return redirect()->route('login');
        }

        $usuario = User::find($id);

        if (!$usuario) {
            session()->forget('id');

            return redirect()->route('login');
        }

        return $next($request);
    }
}
