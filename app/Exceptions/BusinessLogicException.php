<?php
/**
 * 业务异常
 * User: long
 * Date: 2019/7/24
 * Time: 12:22
 */

namespace App\Exceptions;

use App\ResponseTrait;
use Exception;

class BusinessLogicException extends Exception
{
    use ResponseTrait;


    public function render($request)
    {
        return response()->json($this->responseFormat($this->getCode(), [], $this->getMessage()));
    }
}