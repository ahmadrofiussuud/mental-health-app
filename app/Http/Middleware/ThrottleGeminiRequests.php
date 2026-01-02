<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\RateLimiter;

class ThrottleGeminiRequests
{
    /**
     * Handle an incoming request.
     * Limit: 10 requests per minute per user for AI features
     */
    public function handle($request, Closure $next)
    {
        $key = 'gemini_' . ($request->user() ? $request->user()->id : $request->ip());
        
        if (RateLimiter::tooManyAttempts($key, 10)) { // 10 requests per minute
            $seconds = RateLimiter::availableIn($key);
            
            return response()->json([
                'success' => false,
                'error' => "Terlalu banyak request. Coba lagi dalam {$seconds} detik."
            ], 429);
        }
        
        RateLimiter::hit($key, 60); // Reset after 60 seconds
        
        return $next($request);
    }
}
