<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class Require2FASetup
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && ! $request->user()->google2fa_secret) {
            return redirect()->route('2fa.setup');
        }

        return $next($request);
    }
}