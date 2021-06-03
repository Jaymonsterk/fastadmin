<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;

/**
 * 用户提现管理
 *
 * @icon fa fa-circle-o
 */
class UserWithdrawal extends Backend
{
    
    /**
     * UserWithdrawal模型对象
     * @var \app\admin\model\user\UserWithdrawal
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\user\UserWithdrawal;
        $this->view->assign("statusList", $this->model->getStatusList());
    }

    /**
     * 处理状态
     * @param null $ids
     */
    public function handle($ids = null): void
    {
        $params = $this->request->param();
        $this->success($params);
    }
}
