<?php
/**
 * 管理员验证
 * User: long
 * Date: 2019/7/24
 * Time: 16:06
 */

namespace App\Http\Validate\Admin;

use App\Http\Validate\BaseValidate;

class UserValidate extends BaseValidate
{

    public $rules = [
        'id' => ['required', 'integer'],
        'name' => ['required', 'string', 'max:40'],
        'email' => ['required', 'string', 'email', 'max:40', 'unique:users'],
        'password' => ['required', 'string', 'min:6', 'confirmed'],
    ];

    public $scene = [
        'store' => ['name', 'email', 'password'],
        'update' => ['id', 'name'],
        'destroy' => ['id']
    ];

    public $message = [
        'name.require' => '用户名必填'
    ];
}