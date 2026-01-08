<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If not installed and not on install page, redirect to install
        if (!file_exists(storage_path('installed')) && !$request->is('install*') && !$request->is('api/*')) {
            return redirect()->route('install.index');
        }

        // If already installed and trying to access install page, redirect to home
        if (file_exists(storage_path('installed')) && $request->is('install*')) {
            return redirect('/');
        }

        return $next($request);
    }
}
