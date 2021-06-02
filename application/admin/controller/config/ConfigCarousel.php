<?php

namespace app\admin\controller\config;

use app\common\controller\Backend;

/**
 * 轮播配置管理
 *
 * @icon fa fa-circle-o
 */
class ConfigCarousel extends Backend
{
    
    /**
     * ConfigCarousel模型对象
     * @var \app\admin\model\config\ConfigCarousel
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\config\ConfigCarousel;

    }


}
