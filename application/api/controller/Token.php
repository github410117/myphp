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

class Token extends Controller
{



   public static function setAccessToken($clientInfo) {
       //生成token
       $accessToken = self::buildToken();
       $accessTokenInfo = [
           'data' => $clientInfo,
           'accessToken' => $accessToken,
           'expiresTime' => time() + Oauth::$expires
       ];
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

}