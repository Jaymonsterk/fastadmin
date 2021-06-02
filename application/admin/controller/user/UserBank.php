<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;

/**
 * 用户银行卡管理
 *
 * @icon fa fa-circle-o
 */
class UserBank extends Backend
{
    
    /**
     * UserBank模型对象
     * @var \app\admin\model\user\UserBank
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\user\UserBank;

    }


}
