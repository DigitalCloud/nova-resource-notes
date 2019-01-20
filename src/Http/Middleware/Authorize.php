<?php

namespace DigitalCloud\NovaResourceNotes\Http\Middleware;

use DigitalCloud\NovaResourceNotes\NovaResourceNotes;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return resolve(NovaResourceNotes::class)->authorize($request) ? $next($request) : abort(403);
    }
}
