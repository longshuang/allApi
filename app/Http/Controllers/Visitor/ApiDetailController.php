<?php
/**
 * 详情接口
 * User: long
 * Date: 2019/9/16
 * Time: 18:08
 */

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TransactionManager;
use App\Logic\Visitor\ApiDetailLogic;
use Illuminate\Http\Request;

/**
 * Class ApiDetailController
 * @package App\Http\Controllers\Visitor
 * @property ApiDetailLogic $logic
 */
class ApiDetailController extends Controller
{
    public function __construct(Request $request, ApiDetailLogic $logic)
    {
        $this->logic = new TransactionManager($logic);
        $this->data = $request->all();
    }


    public function getInfo()
    {
        return $this->logic->getDetail($this->data);
    }
}