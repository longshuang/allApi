<?php
/**
 * 前端Api接口
 * User: long
 * Date: 2019/7/25
 * Time: 16:34
 */

namespace App\Logic\Visitor;

use App\Exceptions\BusinessLogicException;
use App\Logic\Admin\ApiDetailsLogic;
use App\Logic\Admin\SourceLogic;
use App\Logic\Admin\TypeLogic;
use App\Logic\BaseLogic;
use App\Models\Api;

class ApiLogic extends BaseLogic
{
    public $searchFields = [
        'name' => ['like'],
        'type' => ['=']
    ];

    public function _init()
    {
        $this->model = new Api();
        $this->query = $this->model::query();
    }

    /**
     * 类型 logic
     * @return TypeLogic
     */
    private function getTypeLogic()
    {
        return self::getInstance(TypeLogic::class);
    }

    /**
     * 详情 logic
     * @return ApiDetailsLogic
     */
    private function getApiDetailsLogic()
    {
        return self::getInstance(ApiDetailsLogic::class);
    }

    /**
     * 来源 logic
     * @return SourceLogic
     */
    private function getSourceLogic()
    {
        return self::getInstance(SourceLogic::class);
    }

    /**
     * 列表查询初始化
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function initIndex()
    {
        $data = [];
        $data['typeList'] = $this->getTypeLogic()->getList([], ['id', 'name']);
        return $data;
    }

    /**
     * 详情列表
     * @param $params
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function getDetailList($params)
    {
        $data = $this->getApiDetailsLogic()->getList(['code' => $params['code']]);
        return $data;
    }
}