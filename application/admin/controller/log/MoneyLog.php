<?php

namespace app\admin\controller\log;

use app\common\controller\Backend;

/**
 * 用户资金日志管理
 *
 * @icon fa fa-circle-o
 */
class MoneyLog extends Backend
{
    
    /**
     * MoneyLog模型对象
     * @var \app\admin\model\log\MoneyLog
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\log\MoneyLog;

    }


}
