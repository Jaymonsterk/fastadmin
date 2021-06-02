<?php

namespace app\admin\controller\config;

use app\common\controller\Backend;

/**
 * 用户等级配置
 *
 * @icon fa fa-circle-o
 */
class ConfigVip extends Backend
{
    
    /**
     * ConfigVip模型对象
     * @var \app\admin\model\config\ConfigVip
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\config\ConfigVip;

    }


}
