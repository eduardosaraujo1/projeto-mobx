<?php

namespace App\Http\Middleware;

use App\Facades\SelectedImobiliaria;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasImobiliaria
{
    public function __construct(
    ) {
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $hasImobiliaria = SelectedImobiliaria::get($request->user());

        if (isset($hasImobiliaria)) {
            return $next($request);
        }

        return redirect()->route('imobiliaria.missing');
    }
}
