<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;

/**
 * 用户充值管理
 *
 * @icon fa fa-circle-o
 */
class UserDeposit extends Backend
{
    
    /**
     * UserDeposit模型对象
     * @var \app\admin\model\user\UserDeposit
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\user\UserDeposit;
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
