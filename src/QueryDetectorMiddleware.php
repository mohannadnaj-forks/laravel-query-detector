<?php

namespace BeyondCode\QueryDetector;

use Closure;
use Illuminate\Support\Str;

class QueryDetectorMiddleware
{
    /** @var QueryDetector */
    private $detector;

    public function __construct(QueryDetector $detector)
    {
        $this->detector = $detector;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $this->detector->isEnabled()) {
            return $next($request);
        }

        $this->detector->bootIfNotBooted();

        $this->detector->newContext();

        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        $this->detector->newContext();

        // Modify the response to add the Debugbar
        $this->detector->output($request, $response);

        return $response;
    }
}