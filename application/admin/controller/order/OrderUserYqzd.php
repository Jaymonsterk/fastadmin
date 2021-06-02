<?php

namespace app\admin\controller\order;

use app\common\controller\Backend;

/**
 * 用户邀请做单领取记录管理
 *
 * @icon fa fa-circle-o
 */
class OrderUserYqzd extends Backend
{
    
    /**
     * OrderUserYqzd模型对象
     * @var \app\admin\model\order\OrderUserYqzd
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\order\OrderUserYqzd;

    }


}
