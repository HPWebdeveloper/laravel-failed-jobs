<?php

namespace HPWebdeveloper\LaravelFailedJobs\Http\Middleware;

use HPWebdeveloper\LaravelFailedJobs\Exceptions\ForbiddenException;
use HPWebdeveloper\LaravelFailedJobs\FailedJobs;

class Authenticate
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|null
     */
    public function handle($request, $next)
    {
        if (! FailedJobs::check($request)) {
            throw ForbiddenException::make();
        }

        return $next($request);
    }
}
