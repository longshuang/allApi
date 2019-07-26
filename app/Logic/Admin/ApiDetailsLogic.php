<?php
/**
 * api详情logic
 * User: long
 * Date: 2019/7/26
 * Time: 14:45
 */

namespace App\Logic\Admin;

use App\Exceptions\BusinessLogicException;
use App\Logic\BaseLogic;
use App\Models\ApiDetails;

/**
 * Class ApiDetailsLogic
 * @package App\Logic\Admin
 * @property SourceLogic $sourceLogic
 * @property TypeLogic $typeLogic
 * @property ApiLogic $apiLogic
 */
class ApiDetailsLogic extends BaseLogic
{
    public $searchFields = [
        'type' => ['='],
        'source_id' => ['='],
        'name' => ['like'],
    ];

    public function _init()
    {
        $this->model = new ApiDetails();
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
     * api Logic
     * @return ApiDetailsLogic
     */
    private function getApiLogic()
    {
        return self::getInstance(ApiLogic::class);
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
        $apiInfo = $this->check($param);
        //获取来源和类型
        $param['source_id'] = $apiInfo['source_id'];
        $param['type'] = $apiInfo['type'];
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
     * @return array
     * @throws BusinessLogicException
     */
    protected function check($param)
    {
        //验证code是否存在
        $apiInfo = $this->getApiLogic()->getInfo(['code' => $param['code']]);
        if (empty($apiInfo)) {
            throw new BusinessLogicException('api主表Code不存在', 5003);
        }
        return $apiInfo;
    }
}