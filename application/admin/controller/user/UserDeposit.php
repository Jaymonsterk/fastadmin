<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;

/**
 * 用户充值管理
 *
 * @icon fa fa-circle-o
 */
class UserDeposit extends Backend
{
    
    /**
     * UserDeposit模型对象
     * @var \app\admin\model\user\UserDeposit
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\user\UserDeposit;

    }


}
