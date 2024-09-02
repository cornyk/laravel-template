<?php

namespace App\Middlewares;

use Closure;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequestLogMiddleware
{

    protected const REQUEST_LOG_CHANNEL = 'access';

    /**
     * Handle an incoming request.
     *
     * @param  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        $response = $next($request);
        if ($response instanceof Response) {
            $response->headers->set('Trace-Id', get_trace_id());
        } else {
            $response->header('Trace-Id', get_trace_id());
        }
        return $response;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response $response
     * @param string|null $log
     * @return void
     */
    public function terminate($request, $response, string $log = null): void
    {
        $requestIp = $request->ip();
        $url = $request->path();
        $statusCode = $response->getStatusCode();
        $method = $request->method();
        $bodyParams = http_build_query($request->all());
        $rawBody = preg_replace('/\s(?=\s)/', '', preg_replace("/(\r\n|\n|\r|\t)/i", ' ', $request->getContent()));
        $headers = json_encode($request->header());

        $responseBody = $response->getContent();

        $log = "[{$requestIp}]URL: '{$url}', STATUS_CODE: '{$statusCode}', METHOD: '{$method}', BODY_PARAMS: '{$bodyParams}', RAW_BODY: '{$rawBody}', HEADERS: '{$headers}'; RESPONSE: '{$responseBody}'";
        Log::channel(self::REQUEST_LOG_CHANNEL)->info($log);
    }
}
