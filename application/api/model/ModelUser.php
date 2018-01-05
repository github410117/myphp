<?php
/**
 * Created by PhpStorm.
 * User: yxy
 * Date: 2018/1/4
 * Time: 下午9:52
 */

namespace app\api\model;


use xhtool\base\BaseModel;

class ModelUser extends BaseModel
{
    protected $table = 'xh_admin_user';

    public function checkExistUser($username){
       return $this->where(['username' => $username])->find();
    }
}