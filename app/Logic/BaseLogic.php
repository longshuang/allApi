<?php
/**
 * BaseLogic
 * User: long
 * Date: 2019/7/24
 * Time: 15:19
 */

namespace App\Logic;

use App\FactoryInstanceTrait;
use App\Models\BaseModel;
use App\Exceptions\BusinessLogicException;
use App\WhereTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Log\Logger;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

/**
 * Class BaseLogic
 * @package App\Logic
 * @property BaseModel $model;
 * @property Builder $query;
 */
abstract class BaseLogic
{
    use FactoryInstanceTrait, WhereTrait;

    public $model;

    public $query;

    public $perPage = 10;

    public $searchFields = [];

    protected $data = [];

    public function __construct()
    {
        $this->_init();
    }

    abstract function _init();

    /**
     * 分页查询
     * @param array $where
     * @param array $selectFields
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws BusinessLogicException
     */
    public function getPageList($selectFields = ['*'])
    {
        Log::error('1234567');
        $this->data = request()->all();
        $where = $this->getSearchWhere();
        !empty($where) && WhereTrait::buildWhere($this->query, $where);
        return $this->query->paginate($this->perPage, $selectFields);
    }

    /**
     * 列表查询
     * @param $where
     * @param $selectFields
     * @return array
     * @throws BusinessLogicException
     */
    public function getList($where = [], $selectFields = ['*'])
    {
        !empty($where) && WhereTrait::buildWhere($this->query, $where);
        return $this->query->get($selectFields)->toArray();
    }

    /**
     * 获取详情
     * @param $where
     * @param $selectFields
     * @return array
     * @throws BusinessLogicException
     */
    public function getInfo($where, $selectFields = ['*'])
    {
        $data = $this->getModel($where, $selectFields);
        return !empty($data) ? $data->toArray() : [];
    }

    protected function getSearchWhere()
    {
        $where = [];
        if (empty($this->searchFields) || empty($this->data)) return [];
        foreach ($this->searchFields as $field => $v) {
            //普通条件(=,<>,like等)
            if (count($v) == 1 && !empty($this->data[$field])) {
                $where[$field] = [$v[0], $this->data[$field]];
                continue;
            }
            //between条件
            if ($v[0] == 'between') {
                if (!empty($v[1][0]) && !empty($v[1][1]) && !empty($this->data[$v[1][0]]) && !empty($this->data[$v[1][1]])) {
                    $where[$field] = [$v[0], [$this->data[$v[1][0]], $this->data[$v[1][1]]]];
                    continue;
                }
            }
            // notIn和in条件
            if ((($v[0] == 'in') || $v[0] == 'not in') && !empty($v[1])) {
                $keyArr = is_array($v[1]) ? $v[1] : [$v[1]];
                $valueArr = [];
                foreach ($keyArr as $key) {
                    !empty($this->data[$key]) && array_push($valueArr, $this->data[$key]);
                }
                !empty($valueArr) && $where[$field] = $valueArr;
                continue;
            }
        }
        return $where;
    }


    /**
     * 获取模型
     * @param array $where
     * @param $selectFields
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getModel($where, $selectFields = ['*'])
    {
        WhereTrait::buildWhere($this->query, $where);
        $model = $this->query->first($selectFields);
        return $model;
    }

    /**
     * 新增
     * @param $param
     * @return Builder|\Illuminate\Database\Eloquent\Model
     */
    public function create($param)
    {
        return $this->query->create($param);
    }

    /**
     * 修改
     * @param $param
     * @return int
     */
    public function update($param)
    {
        $this->query->findOrFail($param[$this->model->getKeyName()]);
        $param = Arr::only($param, $this->model->updateFields);
        return $this->query->update($param);
    }

    /**
     * 批量修改
     * @param $where
     * @param $param
     * @return int
     * @throws BusinessLogicException
     */
    public function updateAll($where, $param)
    {
        WhereTrait::buildWhere($this->query, $where);
        return $this->query->update($param);
    }


    /**
     * 删除
     * @param $param
     * @return mixed
     */
    public function destroy($param)
    {
        $this->query->findOrFail($param[$this->model->getKeyName()]);
        return $this->query->delete();
    }

    /**
     * 批量删除
     * @param $where
     * @throws BusinessLogicException
     */
    public function deleteAll($where)
    {
        WhereTrait::buildWhere($this->query, $where);
        $this->query->where($where)->delete();
    }
}