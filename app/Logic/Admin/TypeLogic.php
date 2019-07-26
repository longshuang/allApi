<?php
/**
 * 类型 Logic
 * User: long
 * Date: 2019/7/25
 * Time: 16:34
 */

namespace App\Logic\Admin;

use App\Logic\BaseLogic;
use App\Models\Type;

class TypeLogic extends BaseLogic
{

    public $searchFields = [
        'name' => ['like']
    ];

    public function _init()
    {
        $this->model = new Type();
        $this->query = $this->model::query();
    }


}