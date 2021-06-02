<?php

namespace app\admin\controller\config;

use app\common\controller\Backend;

/**
 * 余额宝配置
 *
 * @icon fa fa-circle-o
 */
class ConfigYuebao extends Backend
{
    
    /**
     * ConfigYuebao模型对象
     * @var \app\admin\model\config\ConfigYuebao
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\config\ConfigYuebao;

    }


}
