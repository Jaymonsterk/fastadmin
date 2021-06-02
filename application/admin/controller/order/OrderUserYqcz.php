<?php

namespace app\admin\controller\order;

use app\common\controller\Backend;

/**
 * 用户邀请充值领取记录管理
 *
 * @icon fa fa-circle-o
 */
class OrderUserYqcz extends Backend
{
    
    /**
     * OrderUserYqcz模型对象
     * @var \app\admin\model\order\OrderUserYqcz
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\order\OrderUserYqcz;

    }


}
