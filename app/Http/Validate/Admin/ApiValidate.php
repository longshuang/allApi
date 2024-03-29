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
        'code' => ['required', 'string', 'max:20', 'uniqueIgnore:apis,id'],
        'type' => ['required', 'integer'],
        'source_id' => ['required', 'integer'],
    ];

    public $scene = [
        'store' => ['name', 'code', 'type', 'source_id'],
        'update' => ['id', 'name', 'code', 'type', 'source_id'],
        'destroy' => ['id']
    ];

    public $message = [

    ];

}