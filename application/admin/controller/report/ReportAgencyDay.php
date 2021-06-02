<?php

namespace app\admin\controller\report;

use app\common\controller\Backend;

/**
 * 单天代理线报表(来源report_user_day)
 *
 * @icon fa fa-circle-o
 */
class ReportAgencyDay extends Backend
{
    
    /**
     * ReportAgencyDay模型对象
     * @var \app\admin\model\report\ReportAgencyDay
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\report\ReportAgencyDay;

    }


}
