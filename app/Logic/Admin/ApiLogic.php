<?php
/**
 * Api Logic
 * User: long
 * Date: 2019/7/25
 * Time: 14:26
 */

namespace App\Logic\Admin;

use App\Exceptions\BusinessLogicException;
use App\Logic\BaseLogic;
use App\Models\Api;

/**
 * Class ApiLogic
 * @package App\Logic\Admin
 */
class ApiLogic extends BaseLogic
{
    public $searchFields = [
        'type' => ['='],
        'source_id' => ['='],
        'name' => ['like'],
    ];


    public function _init()
    {
        $this->model = new Api();
        $this->query = $this->model::query();
    }

    /**
     * 来源Logic
     * @return SourceLogic
     */
    private function getSourceLogic()
    {
        return self::getInstance(SourceLogic::class);
    }

    /**
     * 类型Logic
     * @return TypeLogic
     */
    private function getTypeLogic()
    {
        return self::getInstance(TypeLogic::class);
    }

    /**
     * api详情Logic
     * @return ApiDetailsLogic
     */
    private function getApiDetailsLogic()
    {
        return self::getInstance(ApiDetailsLogic::class);
    }

    /**
     * 列表查询初始化
     * @return array
     * @throws BusinessLogicException
     */
    public function initIndex()
    {
        return $this->initAdd();
    }


    /**
     * 新增初始化
     * @return array
     * @throws BusinessLogicException
     */
    public function initAdd()
    {
        $list = [];
        $list['sourceList'] = $this->getSourceLogic()->getList([], ['id', 'name']);
        $list['typeList'] = $this->getTypeLogic()->getList([], ['id', 'name']);
        return $list;
    }

    /**
     * 新增
     * @param $param
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws BusinessLogicException
     */
    public function create($param)
    {
        $this->check($param);
        return parent::create($param);
    }


    /**
     * 编辑初始化
     * @param $param
     * @return array
     * @throws BusinessLogicException
     */
    public function initEdit($param)
    {
        $data = [];
        $data['list'] = $this->initAdd();
        $data['info'] = parent::getInfo(['id' => $param['id']]);
        return $data;
    }

    /**
     * 修改
     * @param $param
     * @return int
     * @throws BusinessLogicException
     */
    public function update($param)
    {
        $this->check($param);
        //获取旧数据
        $oldInfo = parent::getInfo(['id' => $param['id']]);
        //若来源或类型改变,则改变所有详情接口相应数据来源和类型
        $data = [];
        if (intval($param['source_id']) !== $oldInfo['source_id']) {
            $data['source_id'] = $param['source_id'];
        }
        if (intval($param['type']) !== $oldInfo['type']) {
            $data['type'] = $param['type'];
        }
        if (!empty($data)) {
            $rowCount = $this->getApiDetailsLogic()->updateAll(['code' => $param['code']], $data);
            if ($rowCount === false) {
                throw new BusinessLogicException('详情接口更新失败', 4003);
            }
        }
        return parent::update($param);
    }

    /**
     * 删除
     * @param $param
     * @return mixed|void
     * @throws BusinessLogicException
     */
    public function destroy($param)
    {
        $this->query->findOrFail($param[$this->model->getKeyName()]);
        $code = $this->query->code;
        $rowCount = $this->query->delete();
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败', 4002);
        }
        //删除详情接口相应数据
        $this->getApiDetailsLogic()->deleteAll(['code' => $code]);
    }

    /**
     * 验证
     * @param $param
     * @throws BusinessLogicException
     */
    protected function check($param)
    {
        //验证来源
        $sourceInfo = $this->getSourceLogic()->getInfo(['id' => $param['source_id']]);
        if (empty($sourceInfo)) {
            throw new BusinessLogicException('来源不存在', 5001);
        }
        //验证类型
        $typeInfo = $this->getTypeLogic()->getInfo(['id' => $param['type']]);
        if (empty($typeInfo)) {
            throw new BusinessLogicException('类型不存在', 5002);
        }
    }
}