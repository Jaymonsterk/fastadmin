<?php

namespace app\admin\controller\config;

use app\common\controller\Backend;

/**
 * 邀请充值配置
 *
 * @icon fa fa-circle-o
 */
class ConfigYqcz extends Backend
{
    
    /**
     * ConfigYqcz模型对象
     * @var \app\admin\model\config\ConfigYqcz
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\config\ConfigYqcz;

    }


}
