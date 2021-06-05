<?php

namespace app\admin\controller\log;

use app\common\controller\Backend;

/**
 * 用户操作日志管理
 *
 * @icon fa fa-circle-o
 */
class UserOperationLog extends Backend
{
    
    /**
     * UserOperationLog模型对象
     * @var \app\admin\model\log\UserOperationLog
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\log\UserOperationLog;
        $this->assignconfig("user_operation",array_reverse(config("site.user_operation"),true));
    }


}
