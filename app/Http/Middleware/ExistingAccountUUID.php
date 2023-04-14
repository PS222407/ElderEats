<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExistingAccountUUID
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->has('account_token')) {
            abort(422, 'missing required parameter(s)');
        }
        if (!\App\Models\Account::firstWhere('token', $request->account_token)) {
            abort(404, 'account not found');
        }

        return $next($request);
    }
}
