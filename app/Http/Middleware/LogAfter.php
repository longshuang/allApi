<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/10/24
 * Time: 15:58
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogAfter
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $content = $response->getContent();
        Log::info('返回结果：', ['result' => isJson($content) ? json_decode($content, true) : $content]);
        Log::info('-------------------------请求结束---------------------------------');
        return $response;
    }
}