<?php
/**
 * 管理员
 * User: long
 * Date: 2019/7/24
 * Time: 14:07
 */

namespace App\Logic\Admin;

use App\Exceptions\BusinessLogicException;
use App\Logic\BaseLogic;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserLogic extends BaseLogic
{
    public $searchFields = [
        'name' => ['like']
    ];

    public function _init()
    {
        $this->model = new User();
        $this->query = $this->model::query();
    }

    public function create($param)
    {
        $param['password'] = Hash::make($param['password']);
        parent::create($param);
    }

    public function update($param)
    {
        !empty($param['password']) && ($param['password'] = Hash::make($param['password']));
        return parent::update($param);
    }

    /**
     * 删除
     * @param $param
     * @return mixed
     * @throws BusinessLogicException
     */
    public function destroy($param)
    {
        if (auth()->id() == intval($param[$this->model->getKeyName()])) {
            throw new BusinessLogicException('当前登陆用户不能删除', 4001);
        }
        return parent::destroy($param);
    }

}