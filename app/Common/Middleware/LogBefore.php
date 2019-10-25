<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/10/24
 * Time: 16:03
 */

namespace App\Common\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogBefore
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info('-------------------------请求开始---------------------------------');
        Log::info('请求基本信息：', ['id' => auth()->id(), 'ip' => $request->getClientIp(), 'api' => $request->getRequestUri()]);
        Log::info('请求参数', ['data' => $request->all()]);
        return $next($request);
    }
}