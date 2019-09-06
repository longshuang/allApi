<?php
/**
 * api 验证类
 * User: long
 * Date: 2019/7/25
 * Time: 11:39
 */

namespace App\Http\Validate\Visitor;

use App\Http\Validate\BaseValidate;

class ApiValidate extends BaseValidate
{
    public $rules = [
        'code' => ['required', 'string', 'max:20'],
        'detail_id' => ['required', 'integer']
    ];

    public $scene = [
        'getDetailList' => ['code'],
        'getDetail' => ['detail_id']
    ];

    public $message = [

    ];

}