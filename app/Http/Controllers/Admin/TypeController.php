<?php
/**
 * 类型列表
 * User: long
 * Date: 2019/7/25
 * Time: 16:31
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Logic\Admin\TypeLogic;
use Illuminate\Http\Request;

class TypeController extends Controller
{

    public function __construct(Request $request, TypeLogic $logic)
    {
        $this->logic = $logic;
        $this->data = $request->all();
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
     * 新增
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function store()
    {
        return $this->logic->create($this->data);
    }

    /**
     * 修改
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