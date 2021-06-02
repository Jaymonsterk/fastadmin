<?php

namespace app\admin\controller\report;

use app\common\controller\Backend;

/**
 * 单用户天报表(基础数据表)
 *
 * @icon fa fa-circle-o
 */
class ReportUserDay extends Backend
{
    
    /**
     * ReportUserDay模型对象
     * @var \app\admin\model\report\ReportUserDay
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\report\ReportUserDay;

    }


}
