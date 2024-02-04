<?php

namespace HPWebdeveloper\LaravelFailedJobs\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class AuthorizeFailedJobsByAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): JsonResponse|Response
    {
        // Using `input` method allows checking either query string or request body
        if (config('failedjobs.server_access_token') === $request->input('access_token', false)) {
            return $next($request);
        }

        return new JsonResponse([
            'message' => 'Unauthorized',
        ], Response::HTTP_UNAUTHORIZED);
    }
}
