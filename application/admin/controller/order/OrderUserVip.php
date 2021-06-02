<?php

namespace app\admin\controller\order;

use app\common\controller\Backend;

/**
 * 用户等级订单管理
 *
 * @icon fa fa-circle-o
 */
class OrderUserVip extends Backend
{
    
    /**
     * OrderUserVip模型对象
     * @var \app\admin\model\order\OrderUserVip
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\order\OrderUserVip;

    }


}
