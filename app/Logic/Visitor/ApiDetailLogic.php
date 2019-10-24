<?php
/**
 * Api详情
 * User: long
 * Date: 2019/9/16
 * Time: 18:10
 */

namespace App\Logic\Visitor;

use App\Exceptions\BusinessLogicException;
use App\Logic\Admin\SourceLogic;
use App\Logic\BaseLogic;
use App\Models\ApiDetails;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\App;

class ApiDetailLogic extends BaseLogic
{
    public function _init()
    {
        $this->model = new ApiDetails();
        $this->query = $this->model::query();
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
     * 获取详细信息
     * @param $params
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public function getDetail($params)
    {
        $data = parent::getInfo(['id' => $params['id']]);
        throw_if(empty($data), new BusinessLogicException('接口不存在'));
        $source = $this->getSourceLogic()->getInfo(['id' => $data['source_id']]);
        throw_if(empty($data), new BusinessLogicException('来源不存在'));

        $client = new Client();
        $request = new Request('post', 'http://test.com', ['Content-Type' => 'text/html', 'charset' => 'utf-8']);
        $client->send($request, ['timeout' => 2]);
    }
}