<?php
/**
 * Created by PhpStorm.
 * User: xh
 * Date: 2018/1/2
 * Time: 17:23
 */

namespace app\api\controller;

use think\Response;

trait ReturnCode
{

    public $successCode = 1;//请求成功
    public $paramErrCode = 30;//表单参数错误
    public $missTokenErrCode = 10;//缺少token
    public $serverErrCode = 500;//服务器异常错误
    public $vendorErrCode = 40;//第三方接口错误
    public $dataBaseErrCode = 20;//数据库操作错误
    public $tokenExpiesCode = 50;//token过期

    /**
     * 默认返回类型
     * @var string
     */
    protected $restDefaultType = 'json';

    /**
     * 设置响应类型
     * @param null $type
     * @return $this
     */
    public function setType($type = null)
    {
        $this->type = (string)(!empty($type)) ? $type : $this->restDefaultType;
        return $this;
    }

    /**
     * 失败的响应
     * @param int $status
     * @param string $msg
     * @param int $code
     * @param array $data
     * @param array $headers
     * @param array $options
     * @return mixed
     */
    public function showParamError($msg = 'error', $code = 400, $data = [], $headers = [], $options = [])
    {
        $this->returnMerge($this->paramErrCode,$msg);
    }

    /**
     * 成功的响应
     * @param int $status
     * @param string $msg
     * @param array $data
     * @param int $code
     * @param array $headers
     * @param array $options
     * @return mixed
     */
    public function showSuccess($msg = 'success', $data = [], $code = 200, $headers = [], $options = [])
    {
        $this->returnMerge($this->successCode,$msg);
    }

    protected function returnMerge($status = 400, $msg = '', $data = [], $code = 200, $headers = [], $options = [])
    {
        $responseData['status'] = (int)$status;
        $responseData['msg'] = (string)$msg;
        $responseData['data'] = $data;
        $responseData = array_merge($responseData, $options);
        return $this->response($responseData, $code, $headers);
    }

    /**
     * 响应
     * @param $responseData
     * @param $code
     * @param $headers
     * @return Response|\think\response\Json|\think\response\Jsonp|Redirect|\think\response\View|\think\response\Xml
     */
    public function response($responseData, $code, $headers)
    {
        if (!isset($this->type) || empty($this->type)) $this->setType();
        return Response::create($responseData, $this->type, $code, $headers);
    }

    public function returnmsg($code = '400', $msg = '', $data = [], $header = [])
    {
        http_response_code($code);
        $return['code'] = $code;
        $return['message'] = $msg;
        if (!empty($data)) $return['data'] = $data;

        foreach ($header as $name => $val) {
            if (is_null($val)) {
                $header($name);
            } else {
                $header($name . ':' . $val);
            }
        }
        exit(json_encode($return, JSON_UNESCAPED_UNICODE));
    }


}
