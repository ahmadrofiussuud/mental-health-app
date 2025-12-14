<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        if (!auth()->user()->isSeller()) {
             // If they are not a seller, maybe redirect them to a page to become a seller
             // For now, let's just abort or redirect home
             if (auth()->user()->role === 'admin') {
                 return $next($request); // Admins can access seller dashboard? Maybe not, strict separation usually better.
                 // let's stick to isSeller logic from User model
             }
            
             // Allow admin to bypass? 
             // $this->role === 'admin'
             if(auth()->user()->role === 'admin') {
                return $next($request);
             }

            return redirect()->route('dashboard')->with('error', 'You need to register as a seller first.');
        }

        return $next($request);
    }
}
