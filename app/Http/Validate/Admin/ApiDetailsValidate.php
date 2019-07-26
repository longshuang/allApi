<?php
/**
 * api详情 验证类
 * User: long
 * Date: 2019/7/25
 * Time: 11:39
 */

namespace App\Http\Validate\Admin;

use App\Http\Validate\BaseValidate;

class ApiDetailsValidate extends BaseValidate
{
    public $rules = [
        'id' => ['required', 'integer'],
        'name' => ['required', 'string', 'max:30', 'uniqueIgnore:apis,id'],
        'code' => ['required', 'string', 'max:20'],
        'url' => ['required', 'string', 'max:200', 'url'],
        'request_method' => ['string', 'max:100'],
    ];

    public $scene = [
        'store' => ['name', 'code', 'url', 'request_method'],
        'update' => ['id', 'name', 'code', 'url', 'request_method'],
        'destroy' => ['id']
    ];

    public $message = [

    ];

}