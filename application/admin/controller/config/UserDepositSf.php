<?php

namespace app\admin\controller\config;

use app\common\controller\Backend;

/**
 * 用户充值三方管理
 *
 * @icon fa fa-circle-o
 */
class UserDepositSf extends Backend
{
    
    /**
     * UserDepositSf模型对象
     * @var \app\admin\model\config\UserDepositSf
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\config\UserDepositSf;

    }


}
