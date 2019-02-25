<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiLog;

class AfterPlatformLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Perform action
        $name = $request->getRequestUri();
        $content = $response->getContent() ? $response->getContent() : '';

        if(!$content)
        {
            $status = '';
            $content = '';
        }else{
            $content = json_encode(json_decode($response->getContent(), true), JSON_UNESCAPED_UNICODE);
            $status = json_decode($response->getContent(), true)['status'];
        }
        //插入数据
        $data = [
            "method" => json_encode(['method' => $request->method(), 'url' => $name], JSON_UNESCAPED_UNICODE),
            "type" => 'return',
            "send" => json_encode($request->toArray(), JSON_UNESCAPED_UNICODE),
            "return" => $content,
            "status" => $status,
            "created_at" => date('Y-m-d H:i:s'),
        ];

        ApiLog::insert($data);

        return $response;
    }
}
