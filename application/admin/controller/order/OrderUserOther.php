<?php

namespace app\admin\controller\order;

use app\common\controller\Backend;

/**
 * 用户注册送等记录管理
 *
 * @icon fa fa-circle-o
 */
class OrderUserOther extends Backend
{
    
    /**
     * OrderUserOther模型对象
     * @var \app\admin\model\order\OrderUserOther
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\order\OrderUserOther;

    }


}
