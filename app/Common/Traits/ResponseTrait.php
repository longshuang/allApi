<?php
/**
 * 返回响应trait
 * User: long
 * Date: 2019/7/24
 * Time: 12:08
 */

namespace App\Common\Traits;

trait ResponseTrait
{
    /**
     * @param int $status
     * @param mixed $data
     * @param string $msg
     * @return array
     */
    public function responseFormat($status = 200, $data = null, $msg = 'successful')
    {
        return [
            'status' => $status,
            'data' => $data,
            'msg' => $msg
        ];
    }

    public function jsonResponse($status = 200, $data = null, $msg = 'successful')
    {
        if (is_string($data) && isJson($data)) {
            $data = json_decode($data, JSON_UNESCAPED_UNICODE);
        }
        return response()->json($this->responseFormat($status, $data, $msg));
    }
}