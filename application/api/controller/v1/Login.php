<?php
/**
 * Created by PhpStorm.
 * User: xh
 * Date: 2018/1/3
 * Time: 15:22
 */

namespace app\api\controller\v1;


use app\api\model\ModelUser;
use app\api\validate\V_Login;
use xhtool\base\BaseApiCtr;

class Login extends BaseApiCtr
{

    public function index(){
        return 'ss';
    }

    public function read(){
        return 'kd';
    }

    public function save(){
        $array = [
            'username' => input('param.username'),
            'password' => input('param.password'),
        ];

        $vlogin = new V_Login();
        if (!$vlogin->check($array)) {
            return api_param_error($vlogin->getError());
        }

        $mlogin = new ModelUser();
        if (empty($mlogin->checkExistUser(input('param.username')))){
            return api_error('用户不存在');
        }
        $res = $mlogin->where($array)->find();
        if ($res){
            return api_success($this->request_from);
        }else {
            return api_error('密码错误');
        }

       return api_success('成功');
    }
}