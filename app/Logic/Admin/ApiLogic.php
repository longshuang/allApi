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
 * @property SourceLogic $sourceLogic
 * @property TypeLogic $typeLogic
 */
class ApiLogic extends BaseLogic
{
    public $searchFields = [
        'type' => ['='],
        'source_id' => ['='],
        'name' => ['like'],
    ];

    protected $sourceLogic;

    protected $typeLogic;

    public function _init()
    {
        $this->model = new Api();
        $this->query = $this->model::query();
        $this->sourceLogic = self::getInstance(SourceLogic::class);
        $this->typeLogic = self::getInstance(TypeLogic::class);
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
        $list['sourceList'] = $this->sourceLogic->getList([], ['id', 'name']);
        $list['typeList'] = $this->typeLogic->getList([], ['id', 'name']);
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
        return parent::update($param);
    }

    /**
     * 验证
     * @param $param
     * @throws BusinessLogicException
     */
    protected function check($param)
    {
        //验证来源
        $sourceInfo = $this->sourceLogic->getInfo(['id' => $param['source_id']]);
        if (empty($sourceInfo)) {
            throw new BusinessLogicException('来源不存在', 5001);
        }
        //验证类型
        $typeInfo = $this->typeLogic->getInfo(['id' => $param['type']]);
        if (empty($typeInfo)) {
            throw new BusinessLogicException('类型不存在', 5002);
        }
    }
}