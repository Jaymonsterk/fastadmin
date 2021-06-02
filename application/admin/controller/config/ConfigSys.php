<?php

namespace app\admin\controller\config;

use app\common\controller\Backend;

/**
 * 系统配置管理
 *
 * @icon fa fa-circle-o
 */
class ConfigSys extends Backend
{
    
    /**
     * ConfigSys模型对象
     * @var \app\admin\model\config\ConfigSys
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\config\ConfigSys;

    }


}
