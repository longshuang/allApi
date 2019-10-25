<?php
/**
 * BaseValidate
 * User: long
 * Date: 2019/7/24
 * Time: 17:09
 */

namespace App\Common\Core\Validates;

use App\Common\Core\Models\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

/**
 * Class BaseValidate
 * @package App\Http\Validate
 * @property BaseModel $model;
 */
class BaseValidate
{

    public $rules = [];

    public $scene = [];

    public $message = [];


    /**
     * 唯一验证
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param Validator $validator
     * @return bool
     */
    public function uniqueIgnore($attribute, $value, $parameters, $validator)
    {
        $table = $parameters[0];
        $primaryKey = $parameters[1];
        $data = $validator->getData();
        $query = DB::table($table);
        if (!empty($data[$primaryKey])) {
            $query->where($primaryKey, '<>', $data[$primaryKey]);
        }
        $model = $query->where($attribute, '=', $value)->first();
        return empty($model) ? true : false;
    }
}