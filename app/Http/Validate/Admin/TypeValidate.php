<?php
/**
 * 类型 验证类
 * User: long
 * Date: 2019/7/25
 * Time: 11:39
 */

namespace App\Http\Validate\Admin;

use App\Http\Validate\BaseValidate;

class TypeValidate extends BaseValidate
{
    public $rules = [
        'id' => ['required', 'integer'],
        'name' => ['required', 'string', 'max:30', 'uniqueIgnore:types,id'],
    ];

    public $scene = [
        'store' => ['name'],
        'update' => ['id', 'name'],
        'destroy' => ['id']
    ];

    public $message = [

    ];

}