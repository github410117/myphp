<?php

define('FILE_UPLOAD_PATH', PUBLIC_PATH . 'uploads/');

return [
    // 默认输出类型
    'default_return_type'      => 'json',
    //是否开启安全验证模式，开发环境可关闭
    'safe_mode'                => false,
    'safe_param'               => [
        'validate_token'       => true, //是否验证令牌
        'validate_sign'        => true, //是否验证签名
        'validate_repeat'      => true, //是否验证重复请求
        'repeat_compare_time'  => '3'    //验证重复的比较时间，默认3秒
    ],
    // 前台接口是否开启登录验证模式(不开启会默认用下面模拟数据)
    'f_login_mode'             => false,
    'f_simulation_login_param' => [
        'user_id'              => '10086',
        'user_type'            => 'native',
        'login_time'           => ''
    ],
    // 后台接口是否开启登录验证模式(不开启会默认用下面模拟数据)
    'b_login_mode'             => false,
    'b_simulation_login_param' => [
        'user_id'              => '10000',
        'user_type'            => 'cms',
        'login_time'           => ''
    ],
    // 文件上传路径
    'file_upload_path_base'    => FILE_UPLOAD_PATH,
    'file_upload_path'         => [
        'UserAvator'           => FILE_UPLOAD_PATH . 'UserAvator/', //用户头像
        'Banner'               => FILE_UPLOAD_PATH . 'Banner/', //banner图片
        'Image'                => FILE_UPLOAD_PATH . 'Image/', //content图片
        'Thumb'                => FILE_UPLOAD_PATH . 'Thumb/', //临时图片
        'Article'              => FILE_UPLOAD_PATH . 'Article/', //文章
        'Books'                => FILE_UPLOAD_PATH . 'Books/', //书籍
        'Work'                 => FILE_UPLOAD_PATH . 'Work/', //作品
        'WorkClose'            => FILE_UPLOAD_PATH . 'WorkClose/', //作品集
        'Course'               => FILE_UPLOAD_PATH . 'Course/',//课程
        'Open'                 => FILE_UPLOAD_PATH . 'Open/',//公开课
        'Link'                 => FILE_UPLOAD_PATH . 'Link/',//友情链接
        'Story'                => FILE_UPLOAD_PATH . 'Story/',//公开课
        'Cases'                => FILE_UPLOAD_PATH . 'Cases/',//案例
        'Teacher'              => FILE_UPLOAD_PATH . 'Teacher/',//案例
    ],
];