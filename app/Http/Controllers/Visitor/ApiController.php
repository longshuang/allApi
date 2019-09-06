<?php
/**
 * Api接口
 * User: long
 * Date: 2019/7/26
 * Time: 13:41
 */

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TransactionManager;
use App\Logic\Visitor\ApiLogic;
use Illuminate\Http\Request;

/**
 * Class ApiController
 * @package App\Http\Controllers\Visitor
 * @property ApiLogic $logic
 */
class ApiController extends Controller
{
    public function __construct(Request $request, ApiLogic $logic)
    {
        $this->logic = new TransactionManager($logic);
        $this->data = $request->all();
    }

    /**
     * 列表查询初始化
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function initIndex()
    {
        return $this->logic->initIndex();
    }

    /**
     * 列表查询
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function index()
    {
        return $this->logic->getPageList();
    }

    /**
     * 获取详情列表
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function getDetailList()
    {
        return $this->logic->getDetailList($this->data);
    }

    public function getDetail()
    {
        return $this->logic->getDetail($this->data);
    }
}