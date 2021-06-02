<?php

namespace app\admin\controller\order;

use app\common\controller\Backend;

/**
 * 用户余额宝订单管理
 *
 * @icon fa fa-circle-o
 */
class OrderUserYuebao extends Backend
{
    
    /**
     * OrderUserYuebao模型对象
     * @var \app\admin\model\order\OrderUserYuebao
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\order\OrderUserYuebao;
        $this->view->assign("statusList", $this->model->getStatusList());
    }


}
