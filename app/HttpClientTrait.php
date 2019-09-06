<?php
/**
 * 模拟发送http 请求
 * User: zhangsiwei
 * Date: 2018/8/20
 * Time: 15:29
 */

namespace App;



trait HttpClientTrait
{
    private static $ch;

    /**
     * 设置基础信息
     * @param $url
     * @param array $header
     * @param bool $proxy
     * @param int $expire
     */
    private static function init($url, $header = [], $proxy = false, $expire = 36000)
    {
        self::$ch = curl_init();
        curl_setopt(self::$ch, CURLOPT_URL, $url);
        // 设置代理
        if (!$proxy) {
            curl_setopt(self::$ch, CURLOPT_PROXY, $proxy);
            $proxyJson = json_encode($proxy);
            Log::info("******************* post:proxy=##{$proxyJson}##");
        }
        // 设置SSL
        $isSSL = substr($url, 0, 8) == 'https://' ? true : false;
        if ($isSSL) {
            curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
            curl_setopt(self::$ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        }
        // 设置请求header
        if (!empty($header)) {
            curl_setopt(self::$ch, CURLOPT_HTTPHEADER, $header);
            $headerJson = json_encode($header);
            Log::info("******************* post:header=##{$headerJson}##");
        }
        curl_setopt(self::$ch, CURLOPT_FOLLOWLOCATION, true);   // 使用自动跳转
        //下面发送一个常规的POST请求，类型为application/x-www-form-urlencoded,就像提交表单一样
        curl_setopt(self::$ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.2; rv:19.0) Gecko/20100101 Firefox/19.0");
        curl_setopt(self::$ch, CURLOPT_HEADER, false);
        curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, true);   // 结果集的形式返回
        curl_setopt(self::$ch, CURLOPT_TIMEOUT, $expire); // 设置cURL允许执行的最长秒数。
    }


    /**
     * 发送POST请求
     * @param string $url 发送地址
     * @param array $data 发送报文
     * @param array $header 发送头
     * @param bool $proxy 代理信息
     * @param int $expire 超时时间
     * @return bool|mixed
     */
    public static function post($url, $data = [], $header = [], $proxy = false, $expire = 36000)
    {
        $dataJson = json_encode($data);
        Log::info("******************* post:start,url=[{$url}],data=##{$dataJson}##");
        self::init($url, $header, $proxy, $expire);
        // POST发送数据
        curl_setopt(self::$ch, CURLOPT_POST, true);//发送一个常规的Post请求
        curl_setopt(self::$ch, CURLOPT_POSTFIELDS, $data);//Post提交的数据包
        $response = false;
        try {
            // 执行发送CURL
            $response = curl_exec(self::$ch);
            Log::info("******************* post:response=##{$response}##");
            if (curl_errno(self::$ch)) {
                $curlError = curl_error(self::$ch);
                // 错误信息处理
                //Log::error("******************* post:curlError=##{$curlError}##");
                throw new Exception($curlError);
            }
            $httpCode = curl_getinfo(self::$ch, CURLINFO_HTTP_CODE);
            if ($httpCode != 200) {
                Log::error("******************* post:httpCode=##{$httpCode}##");
                throw new Exception("httpCode=##{$httpCode}");
            }
            curl_close(self::$ch);
            Log::info("******************* post:end,url=[{$url}],response=##{$response}##");
        } catch (Exception $e) {
            $message = "code:{$e->getCode()}, message:{$e->getMessage()} file:{$e->getFile()} line:{$e->getLine()}";
            Log::error("******************* post:error=##{$message}##");
        }
        return $response;
    }

    /**
     * 获取请求的域名
     *
     * @author zhangkui
     * @time 2018-03-14 10:53
     */
    public static function getRequestHttpHost()
    {
        $request_http_host = constant('REQUEST_HTTP_HOST');
        if (empty($request_http_host)) {
            $request_http_host = $_SERVER["HTTP_HOST"];
        }
        //简单判断 请求host是域名还是IP
        if (filter_var($request_http_host, FILTER_VALIDATE_IP)) {
            $request_http_host = 'localhost';
        }
        return $request_http_host;
    }


    /**
     * 金条签名验证
     * @param $url
     * @param array $data
     * @param string $method
     * @param array $header
     * @param int $expire
     * @return mixed
     */
    public static function sendGoldSignAPIRequest($url, $data = [], $method = '', $header = [], $expire = 36000)
    {
        if (empty($header)) {
            $header = ['Content-Type: application/json'];
        }
        $data = array_merge($data, ['_request_http_host' => self::getRequestHttpHost()]);
        $result = self::post($url, json_encode($data), $header);
        return json_decode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 发送API请求
     * @param $url
     * @param array $data
     * @param string $method
     * @param array $header
     * @param int $expire
     * @return mixed
     */
    public static function sendAPIRequest($url, $data = [], $method = '', $header = [], $expire = 36000)
    {
        if (empty($header)) {
            $header = ['Content-Type: application/json'];
        }
        $config = Config::get('api');
        $sendData = [
            '_request_http_host' => $config['api_request_host'],
            'caller' => $config['api_caller'],
            'callee' => $config['api_callee'],
            'eventid' => rand(1000, 9999),
            'timestamp' => time(),
            'method' => $method,
            'data' => $data,
        ];
        $sendData = json_encode($sendData);
        $result = self::post($url, $sendData, $header, false, $expire);
        return json_decode($result, true);
    }

    /**
     * 金条API请求
     * @param $url
     * @param array $data
     * @param string $method
     * @param array $header
     * @param int $expire
     * @return mixed
     * @author: lsh
     */
    public static function sendAPIRequestPaySys($url, $data = [], $method = '', $header = [], $expire = 36000)
    {
        if (empty($header)) {
            $header = ['Content-Type: application/json'];
        }
        $config = Config::get('gold_config.GOLD_API_CONFIG');
        $data['_request_http_host'] = self::getRequestHttpHost();
//        $data['_request_http_host'] = Config::get('PAY_SYS_IP');

        $sendData = [
            '_request_http_host' => self::getRequestHttpHost(),
//            '_request_http_host' => Config::get('PAY_SYS_IP'),
            'caller' => $config['api_caller'],
            'callee' => $config['api_callee'],
            'eventid' => rand(1000, 9999),
            'timestamp' => time(),
            'method' => $method,
            'data' => $data,
        ];

        Log::info("********** openssl_sign start **********111");
        //签名开始
        $privateKeyPath = Config::get('gold_config.HTTP_API_KEY');
        Log::info("********** openssl_sign start **********" . $privateKeyPath);
        $signInfo = '';
        $sign_data = $sendData['data'];
        if (file_exists($privateKeyPath)) {
            $privateKey = file_get_contents($privateKeyPath);
            Log::info("********** openssl_sign start **********" . $privateKeyPath);

            ksort($sign_data);
            $sign_data = json_encode($sign_data);
            $pi_key = openssl_pkey_get_private($privateKey);
            openssl_sign($sign_data, $signInfo, $pi_key, OPENSSL_ALGO_SHA1);
            $signInfo = base64_encode($signInfo);
            Log::info('********** openssl_sign end $singInfo=' . $signInfo . '**********');
        } else {
            Log::error('requestPaySys openssl_sign key null');
            Log::error("********** requestPaySys openssl_sign key null **********");
        }
        $sendData['signInfo'] = $signInfo;
        //签名结束

        $sendData = json_encode($sendData);
        $url = Config::get('PAY_SYS_URL') . $url;
        $result = self::post($url, $sendData, $header, false, $expire);
        return json_decode($result, true);
    }

    /**
     * RSA加密发送请求
     * @param $url
     * @param array $data
     * @param string $method
     * @param array $header
     * @param int $expire
     * @return mixed
     * @throws MessageException
     */
    public static function sendAPIByRSARequest($url, $data = [], $method = '', $header = [], $expire = 36000)
    {
        if (empty($header)) {
            $header = ['Content-Type: application/json'];
        }
        //数据加密
        $data = RSAUtil::publicKeyEncrypt(Config::get('rsa_public_key'), $data);
        $data = json_encode(['data' => $data], JSON_UNESCAPED_UNICODE);
        $result = self::post($url, $data, $header, false, $expire);
        return json_decode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 发送好会付API请求
     * @param array $data
     * @param string $method
     * @param array $header
     * @param int $expire
     * @return mixed
     * @throws MessageException
     */
    public static function sendHHFAPIRequest($data = [], $method = '', $header = [], $expire = 36000)
    {
        if (empty($header)) {
            $header = ['Content-Type: application/json'];
        }
        $config = Config::get('hhf_api');
        //获取third_host，若为未登陆状态,则直接传;若为登陆状态，则他通过登陆信息获取
        $jsonData = json_encode($data);
        Log::write("--------------jsonData:{$jsonData}");
        if (!empty($data['third_host'])) {
            $thirdHost = $data['third_host'];
            $thirdCaller = $data['third_caller'];
            $thirdPrivateKey = $data['third_private_key'];
            unset($data['third_host'], $data['third_caller'], $data['third_private_key']);
        } else {
            $user = UserUtil::getLoginUser();
            $redisManager = new \app\erp\common\util\RedisManager();
            $institutionalRedis = $redisManager->getCacheData(BaseConst::_REDIS_INSTITUTIONAL_KEY, $user['institutional_id'], $user['company_code'], '*');
            $thirdHost = !empty($institutionalRedis['third_host']) ? $institutionalRedis['third_host'] : null;
            $thirdCaller = !empty($institutionalRedis['third_caller']) ? $institutionalRedis['third_caller'] : null;
            $thirdPrivateKey = !empty($institutionalRedis['third_private_key']) ? $institutionalRedis['third_private_key'] : null;
        }
        if (empty($thirdHost)) {
            throw new MessageException('请先绑定机构');
        }
        Log::write("--------------third_host:{$thirdHost},third_caller:{$thirdCaller},third_private_key:{$thirdPrivateKey}");
        $url = $config['api_url'];
        /*$data = [
            'loginid' => 'G00010013',
            'loginpwd' => md5('KKun#123456'),
            'thirdid' => 'GYR-G0001',
            'smscode' => '1234'
        ];*/
        //数据参数
        $data_params = [];
        $data_params['uid'] = null;
        $data_params['timestamp'] = getMicroTime();
        $data_params['callmethod'] = $method;
        $data_params['version'] = "v1";
        $data_params['isdebug'] = "1";
        $data_params['data'] = json_encode($data);
        $_data = json_encode($data_params);

//        $openSSLAES = new OpenSSLAES($config['api_private_key']);
        $openSSLAES = new OpenSSLAES($thirdPrivateKey);
        $_data = $openSSLAES->encrypt($_data);
        $sendData = [
            '_host' => $thirdHost,
            '_caller' => $thirdCaller,
            '_callmethod' => $method,
            '_timestamp' => $data_params['timestamp'],
            '_eventid' => randnumb(8),
            '_loginkey' => null,
            '_data' => $_data,
        ];
        $sendData = json_encode($sendData);
        $result = self::post($url, $sendData, $header, false, $expire);
        return json_decode($result, true);
    }

    /**
     * 数据上链API请求
     * @param $url
     * @param $method
     * @param $params
     * @param $auth
     * @return mixed
     * @author: lsh
     */
    public static function sendBlockchainAPIRequest($url, $method, $params=[], $header=[], $proxy = false, $expire = 36000){

        if (empty($header)) {
            $header = ['Content-Type: application/json'];
        }

        self::init($url, $header, $proxy, $expire);

        //不同请求方法的数据提交
        switch ($method){
            case "GET" :
                curl_setopt(self::$ch, CURLOPT_HTTPGET, true);//TRUE 时会设置 HTTP 的 method 为 GET，由于默认是 GET，所以只有 method 被修改时才需要这个选项。
                break;
            case "POST":
                if(is_array($params)){
                    $params = json_encode($params,320);
                }
                // POST发送数据
                curl_setopt(self::$ch, CURLOPT_POST, true);//发送一个常规的Post请求
                curl_setopt(self::$ch, CURLOPT_POSTFIELDS, $params);//Post提交的数据包
                break;
            case "PUT" :
                if(is_array($params)){
                    $params = json_encode($params,320);
                }
                curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt(self::$ch, CURLOPT_POSTFIELDS,$params);
                break;
            case "DELETE":
                curl_setopt (self::$ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt(self::$ch, CURLOPT_POSTFIELDS,$params);
                break;
        }

        $response = false;
        try {
            // 执行发送CURL
            $response = curl_exec(self::$ch);
            Log::info("******************* post:response=##{$response}##");
            if (curl_errno(self::$ch)) {
                $curlError = curl_error(self::$ch);
                // 错误信息处理
                //Log::error("******************* post:curlError=##{$curlError}##");
                throw new Exception($curlError);
            }
            $httpCode = curl_getinfo(self::$ch, CURLINFO_HTTP_CODE);
            if ($httpCode != 200) {
                Log::error("******************* post:httpCode=##{$httpCode}##");
                throw new Exception("httpCode=##{$httpCode}");
            }
            curl_close(self::$ch);
            Log::info("******************* post:end,url=[{$url}],response=##{$response}##");
        } catch (Exception $e) {
            $message = "code:{$e->getCode()}, message:{$e->getMessage()} file:{$e->getFile()} line:{$e->getLine()}";
            Log::error("******************* post:error=##{$message}##");
        }
        return $response;
    }

}