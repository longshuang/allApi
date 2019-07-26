<?php
/**
 * 来源Logic
 * User: long
 * Date: 2019/7/25
 * Time: 11:36
 */

namespace App\Logic\Admin;

use App\Logic\BaseLogic;
use App\Models\Source;

class SourceLogic extends BaseLogic
{
    public function _init()
    {
        $this->model = new Source();
        $this->query = $this->model::query();
    }

}