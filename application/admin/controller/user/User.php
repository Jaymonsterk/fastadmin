<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;

/**
 * 用户管理
 *
 * @icon fa fa-user
 */
class User extends Backend
{
    
    /**
     * User模型对象
     * @var \app\admin\model\user\User
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\user\User;
        $this->view->assign("isfistdepositList", $this->model->getIsfistdepositList());
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->assignconfig('vip_list',get_vip_list());
    }


}
