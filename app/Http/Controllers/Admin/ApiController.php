<?php
/**
 * Api接口
 * User: long
 * Date: 2019/7/25
 * Time: 14:25
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Logic\Admin\ApiLogic;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function __construct(Request $request, ApiLogic $logic)
    {
        $this->logic = $logic;
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
     * 新增初始化
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function initAdd()
    {
        return $this->logic->initAdd();
    }

    /**
     * 新增
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function store()
    {
        return $this->logic->create($this->data);
    }

    /**
     * 编辑初始化
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function initEdit()
    {
        return $this->logic->initEdit($this->data);
    }

    /**
     * 修改
     * @param Request $request
     * @return int
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function update()
    {
        return $this->logic->update($this->data);
    }


    public function destroy()
    {
        return $this->logic->destroy($this->data);
    }

}