<?php
/**
 * Created by PhpStorm.
 * User: xh
 * Date: 2018/1/3
 * Time: 15:22
 */

namespace app\api\controller\v1;


use app\api\controller\Token;
use app\api\model\ModelUser;
use app\api\validate\V_Login;
use think\cache\driver\Redis;
use think\facade\Cache;
use xhtool\base\BaseApiCtr;



class Login extends BaseApiCtr
{

//    public $restMethodList = 'post';

    public function index(){
        return api_success('称呼你刚刚');
    }

    /**
     * 登录
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
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
            unset($res['password']);
            $token = Token::setAccessToken($res);
            return api_success($token);
        }else {
            return api_error('密码错误');
        }
    }
}