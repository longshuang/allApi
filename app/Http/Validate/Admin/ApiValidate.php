<?php
/**
 * api 验证类
 * User: long
 * Date: 2019/7/25
 * Time: 11:39
 */

namespace App\Http\Validate\Admin;

use App\Http\Validate\BaseValidate;

class ApiValidate extends BaseValidate
{
    public $rules = [
        'id' => ['required', 'integer'],
        'name' => ['required', 'string', 'max:30', 'uniqueIgnore:apis,id'],
        'code' => ['required', 'string', 'max:20'],
        'url' => ['required', 'string', 'max:200', 'url'],
        'type' => ['required', 'integer'],
        'source_id' => ['required', 'integer'],
        'request_method' => ['string', 'max:100'],
    ];

    public $scene = [
        'store' => ['name', 'code', 'url', 'type', 'source_id', 'request_method'],
        'update' => ['id', 'name', 'code', 'url', 'type', 'source_id', 'request_method'],
        'destroy' => ['id']
    ];

    public $message = [

    ];

}