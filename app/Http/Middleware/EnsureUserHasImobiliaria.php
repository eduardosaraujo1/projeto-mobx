<?php

namespace App\Http\Middleware;

use App\Services\ImobiliariaService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasImobiliaria
{
    public function __construct(
        protected ImobiliariaService $service
    ) {
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $hasImobiliaria = $this->service->getSelectedImobiliaria();

        if (isset($hasImobiliaria)) {
            return $next($request);
        }

        return redirect()->route('imobiliaria.missing');
    }
}
