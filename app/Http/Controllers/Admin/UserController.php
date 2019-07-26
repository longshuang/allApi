<?php
/**
 * 管理员
 * User: long
 * Date: 2019/7/24
 * Time: 13:45
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Logic\Admin\UserLogic;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct(Request $request, UserLogic $logic)
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

    public function store()
    {
        return $this->logic->create($this->data);
    }

    public function update()
    {
        return $this->logic->update($this->data);
    }

    /**
     * 删除
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function destroy()
    {
        return $this->logic->destroy($this->data);
    }
}