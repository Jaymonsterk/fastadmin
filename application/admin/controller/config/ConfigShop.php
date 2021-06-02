<?php

namespace app\admin\controller\config;

use app\common\controller\Backend;

/**
 * 商品配置
 *
 * @icon fa fa-circle-o
 */
class ConfigShop extends Backend
{
    
    /**
     * ConfigShop模型对象
     * @var \app\admin\model\config\ConfigShop
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\config\ConfigShop;
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->assignconfig('vip_list',get_vip_list());
    }


}
