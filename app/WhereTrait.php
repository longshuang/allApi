<?php
/**
 * 设置where
 * User: long
 * Date: 2019/7/24
 * Time: 14:49
 */

namespace App;

use App\Exceptions\BusinessLogicException;
use Illuminate\Database\Eloquent\Builder;

trait WhereTrait
{

    /**
     * 设置where
     * @param Builder $query
     * @param $data
     * @throws BusinessLogicException
     */
    public static function buildWhere(Builder $query, $data)
    {
        foreach ($data as $key => $item) {
            //获取字段名,操作符,值
            $field = $key;
            if (is_array($item)) {
                $op = $item[0];
                $value = $item[1];
            } else {
                $op = '=';
                $value = $item;
            }
            switch ($op) {
                case in_array($op, ['=', '<>', '<', '>']):
                    $query->where($field, $op, $value);
                    break;
                case 'like':
                    $query->where($field, $op, "%$value%");
                    break;
                case 'between':
                    $query->whereBetween($field, [$value[0], $value[1]]);
                    break;
                case 'in':
                    $query->whereIn($field, $value);
                    break;
                case 'not in':
                    $query->whereNotIn($field, $value);
                    break;
                default :
                    throw new BusinessLogicException('参数错误', 2002);
            }
        }
    }

}