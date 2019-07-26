<?php
/**
 * 来源验证类
 * User: long
 * Date: 2019/7/25
 * Time: 11:39
 */

namespace App\Http\Validate\Admin;

use App\Http\Validate\BaseValidate;

class SourceValidate extends BaseValidate
{
    public $rules = [
        'id' => ['required', 'integer'],
        'name' => ['required', 'string', 'max:30', 'uniqueIgnore:source,id'],
        'app_key' => ['required', 'string', 'max:100'],
        'app_secret' => ['required', 'string', 'max:100'],
    ];

    public $scene = [
        'store' => ['name', 'app_key', 'app_secret'],
        'update' => ['id', 'name', 'app_key', 'app_secret'],
        'destroy' => ['id']
    ];

    public $message = [
        'name.uniqueIgnore' => '名称已存在!'
    ];

}