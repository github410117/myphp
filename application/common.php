<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use xhtool\http\ApiOut;
use xhtool\rsa\RSA;
/************ API响应 *************/
/**
 * @param string $data
 * @param int $code
 * @param string $type
 * @param array $header
 * @param array $options
 * @return think\response
 */
function http_response($data = '非法请求', $code = 403, $type = 'html', array $header = [], $options = []) {
    return ApiOut::httpResponse($data,$type,$code,$header,$options);
}

function api_success($data = '', array $header = [], $options = []) {
    return ApiOut::success($data,$header,$options);
}

function api_error($msg = '', array $header = [], $options = []) {
    return ApiOut::error($msg,$header,$options);
}

function api_param_error($msg = '', array $header = [], $options = []) {
    return ApiOut::paramError($msg,$header,$options);
}

function api_sign_error(array $header = [], $options = []) {
    return ApiOut::signError($header,$options);
}

function api_token_error(array $header = [], $options = []) {
    return ApiOut::tokenError($header,$options);
}

function api_repeat_error(array $header = [], $options = []) {
    return ApiOut::repeatError($header,$options);
}

function api_server_error(array $header = [], $options = []) {
    return ApiOut::serverError($header,$options);
}

function api_unknown_error(array $header = [], $options = []) {
    return ApiOut::unknownError($header,$options);
}

function api_not_permission(array $header = [], $options = []) {
    return ApiOut::notPermission($header,$options);
}

function api_list_not_more(array $header = [], $options = []) {
    return ApiOut::listNotMore($header,$options);
}

function api_login_overdue(array $header = [], $options = []) {
    return ApiOut::loginOverdue($header,$options);
}

function api_login_elsewhere(array $header = [], $options = []) {
    return ApiOut::loginElsewhere($header,$options);
}

/************** 加密、解密 *************
 *
 * 有3套密钥
 * 一套前端放公钥后端放私钥，用于前端加密数据传后端解密
 * 一套前端放私钥后端放公钥，用于后端加密数据传前端解密
 * 一套自己加密自己解密，用于构建Session、Token等传输到前端再传回来的数据
 * (JS环境不支持私钥加密公钥解密)
 */
/**
 * RSA用公钥加密(传给前端解密)
 * @param $text string
 * @return string
 */
function rsa_encrypt_to_fore($text) {
    return RSA::encrypt_to_fore($text);
}

/**
 * RSA用私钥加密(前端传过来解密)
 * @param $text string
 * @return string
 */
function rsa_decrypt_from_fore($text) {
    return RSA::decrypt_from_fore($text);
}

/**
 * RSA用公钥加密(用于自己解密)
 * @param $text string
 * @return string
 */
function rsa_encrypt_myself($text) {
    return RSA::encrypt_myself($text);
}

/**
 * RSA用私钥解密(用于自己加密)
 * @param $text string
 * @return string
 */
function rsa_decrypt_myself($text) {
    return RSA::decrypt_myself($text);
}

/************ Session *************/
/**
 * 构建SessionId
 * @param $value string 值
 * @param string $module 模块名
 * @return string
 */
function build_session_id($value,$module = 'unknown') {
    return rsa_encrypt_myself($module.'<::>'.$value);
}

/**
 * 从session_id中获取其信息
 * @param $session_id
 * @return array
 */
function get_info_by_session_id($session_id) {
    $info = [];
    $decrypt = rsa_decrypt_myself($session_id);
    $split = explode('<::>',$decrypt);
    if(count($info) === 2) {
        $info['module'] = $split[0];
        $info['value'] = $split[1];
    }
    return $info;
}

/**
 * 构建UserSessionId 每个用户的UserSessionId 应该是一样的。所以不能加上时间。
 * @param string $user_id
 * @param string $user_type
 * @return string
 */
function build_user_session_id($user_id = 'unknown',$user_type = \app\api\controller\ApiCommon::USER_TYPE_NATIVE) {
    $value = $user_type.'##'.$user_id;
    $module = 'login';
    return build_session_id($value,$module);
}

/**
 * 构建UserSessionId
 * @param string $user_type
 * @return string
 */
//function build_user_session_id($user_type = \app\api\controller\ApiCommon::USER_TYPE_NATIVE) {
//    $value = $user_type.'##'.time();
//    $module = 'login';
//    return build_session_id($value,$module);
//}

/**
 * 构建UserKey
 * @param $user_id int|string 用户ID
 * @param $user_type string 用户类型
 * @return string
 */
function build_user_key($user_id = 'unknown',$user_type = \app\api\controller\ApiCommon::USER_TYPE_NATIVE) {
    $value = $user_type.'##'.$user_id.'##'.time();
    return rsa_encrypt_myself($value);
}

/**
 * 从user_key中获取用户信息(用户ID、登录时间、是否后台用户)
 * @param $user_key
 * @return array
 */
function get_info_by_user_key($user_key) {
    $info = [];
    $decrypt = rsa_decrypt_myself($user_key);
    $split = explode('##',$decrypt);
    if (count($split) === 3) {
        $info['user_type'] = $split[0];
        $info['user_id'] = $split[1];
        $info['login_time'] = date('Y-m-d H:i:s',$split[2]);
    }
    return $info;
}


/************ 其他 *************/
/**
 * 对上传图片的路径添加域名前缀(用于输出全路径给前端)
 * @param $image_path
 * @return mixed
 */
function upload_image_path_format($image_path) {
    return str_replace('./',config('upload_host'),$image_path);
}

/**
 * 对整个数组里面的图片地址进行添加域名前缀
 * @param array $array
 * @param string $key
 */
function list_upload_image_path_format($array = [],$key = 'img_path') {
    if (!is_array($array))
        return;
    foreach ($array as $i) {
        if (!isset($i[$key]))
            continue;
        $i[$key] = upload_image_path_format($i[$key]);
    }
}


/**
 * 求出 $arr1 不在$arr2中的元素
 * @param $arr1
 * @param $arr2
 * @return array
 */
function array1_not_in_array2($arr1,$arr2){

    if(!is_array($arr1)){
        $arr1 = explode(',',$arr1);
    }
    if(!is_array($arr2)){
        $arr2 = explode(',',$arr2);
    }

    $new_arr = array();
    foreach($arr1 as $k=>$v){
        if(!in_array($v,$arr2)){   //不在数据库中，说明是新增的
            $new_arr[] = $v;
        }
    }
    return $new_arr;
}

function allListGetType($list,$faData){

    foreach ($list as $key => $value){
        $arr1 = array();
        foreach ($faData as $fa_value){
            $arr = array();
            foreach ($value['items'] as $item) {

                if ($fa_value['id'] == $item['type']['pid']) {
                    $arr[] = $item['type']['name'];
                }
            }
            if($arr != null){
                $arr1[] = implode('，', $arr);
            }
        }
        $value['type_str'] = implode('／',$arr1);
        unset($value['items']);
    }

    return $list;
}
