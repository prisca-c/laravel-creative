<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            if ($user->admin()) {
                return $next($request);
            } else {
                return redirect(route('dashboard'));
            }
        }

        abort(403);  // permission denied error
    }
}
