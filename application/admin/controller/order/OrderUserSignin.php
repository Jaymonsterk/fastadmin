<?php

namespace app\admin\controller\order;

use app\common\controller\Backend;

/**
 * 用户签到订单管理
 *
 * @icon fa fa-circle-o
 */
class OrderUserSignin extends Backend
{
    
    /**
     * OrderUserSignin模型对象
     * @var \app\admin\model\order\OrderUserSignin
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\order\OrderUserSignin;
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->assignconfig("conifg_signin",config("site.conifg_signin"));
    }


}
