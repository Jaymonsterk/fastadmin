<?php

namespace app\admin\controller\config;

use app\common\controller\Backend;

/**
 * 邀请做单配置
 *
 * @icon fa fa-circle-o
 */
class ConfigYqzd extends Backend
{
    
    /**
     * ConfigYqzd模型对象
     * @var \app\admin\model\config\ConfigYqzd
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\config\ConfigYqzd;

    }


}
