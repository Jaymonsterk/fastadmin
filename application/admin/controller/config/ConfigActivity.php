<?php

namespace app\admin\controller\config;

use app\common\controller\Backend;

/**
 * 活动配置管理
 *
 * @icon fa fa-circle-o
 */
class ConfigActivity extends Backend
{
    
    /**
     * ConfigActivity模型对象
     * @var \app\admin\model\config\ConfigActivity
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\config\ConfigActivity;

    }


}
