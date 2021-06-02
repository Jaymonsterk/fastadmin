<?php

namespace app\admin\controller\order;

use app\common\controller\Backend;

/**
 * 用户任务订单管理
 *
 * @icon fa fa-circle-o
 */
class OrderUserShop extends Backend
{
    
    /**
     * OrderUserShop模型对象
     * @var \app\admin\model\order\OrderUserShop
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\order\OrderUserShop;
        $this->view->assign("statusList", $this->model->getStatusList());
    }


}
