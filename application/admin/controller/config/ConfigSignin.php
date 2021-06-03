<?php

namespace app\admin\controller\config;

use app\common\controller\Backend;

/**
 * 用户签到配置
 *
 * @icon fa fa-circle-o
 */
class ConfigSignin extends Backend
{
    
    /**
     * ConfigSignin模型对象
     * @var \app\admin\model\config\ConfigSignin
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\config\ConfigSignin;
        $this->assignconfig("conifg_signin",array_reverse(config("site.conifg_signin"),true));
    }


}
