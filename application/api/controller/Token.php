<?php
/**
 * Created by PhpStorm.
 * User: xh
 * Date: 2018/1/3
 * Time: 11:45
 */

namespace app\api\controller;

use app\api\controller\Oauth as Oauth2;
use think\Controller;
use think\facade\Cache;

class Token extends Controller
{


    /**
     *
     * @param $clientInfo
     * @return array
     */
   public static function setAccessToken($clientInfo) {
       $phone = $clientInfo['username'];
       //生成token
       $accessToken = self::buildToken($phone);
       $accessTokenInfo = [
           'userinfo' => $clientInfo,
           'accessToken' => $accessToken,
//           'expiresTime' => time() + Oauth::$expires
       ];
       self::saveToken($phone,$accessToken);
       return $accessTokenInfo;
   }


    /**
     * 生成token
     * @param string $phone
     * @return string
     */
    protected static function buildToken($phone = ''){
        $str = md5(uniqid(md5(microtime(true)), true));
        return sha1($str.$phone);
    }

    /**
     * 保存token
     * @param string $phone
     */
    protected static function saveToken($phone = '',$btoken = ''){
        try {
            Cache::store('redis')->set($phone,$btoken);
        } catch (\Exception $e) {
            return api_resid_error();
        }
    }

}