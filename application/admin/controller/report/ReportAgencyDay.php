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

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {

            $has_time = $this->params();
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $field = "uid,uname,upuid,upuname,reportid,id,path,dates, ";
            $field .= " downnum,alldownnum,sum(`isnew`) as isnew, ";
            $field .= " sum(`isfistdeposit`) as isfistdeposit, sum(`usemoney`) as usemoney, sum(`reward`) as reward, ";
            $field .= " sum(`commission`) as commission, sum(`totalnum`) as totalnum, sum(`successnum`) as successnum, ";
            $field .= " sum(`failnum`) as failnum, sum(`depositmoney`) as depositmoney, sum(`depositnum`) as depositnum, ";
            $field .= " sum(`withdrawalsmoney`) as withdrawalsmoney, sum(`withdrawalsnum`) as withdrawalsnum, sum(`zcsmoney`) as zcsmoney, ";
            $field .= " sum(`qdmoney`) as qdmoney, sum(`yqzdmoney`) as yqzdmoney, sum(`yqczmoney`) as yqczmoney, sum(`yebmoney`) as yebmoney";

            $list = $this->model
                ->field($field)
                ->where($where)
                ->group('uid')
                ->order($sort, $order)
                ->paginate($limit);

            if($has_time) {
                foreach ($list as $row) {
                    $row['dates'] = "范围时间";
                }
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 处理默认参数
     */
    protected function params()
    {
        $today = date("Y-m-d");
        $today_range = $today." 00:00:00 - ".$today." 23:59:59";
        $time_op = "RANGE";

        //字段
        $tmp_fields = $this->request->get("filter", '');
        $where_fields = (array)json_decode($tmp_fields, true);
        $has_time = true;
        if(!isset($where_fields['ctime']) || empty($where_fields['ctime'])){
            $has_time = false;
            $where_fields['ctime'] = $today_range;
        }

        //操作符
        $tmp_op = $this->request->get("op", '');
        $where_op = (array)json_decode($tmp_op, true);
        if(!isset($where_op['ctime']) || empty($where_op['ctime'])){
            $where_op['ctime'] = $time_op;
        }

        $tmp_fields = json_encode($where_fields);
        $tmp_op = json_encode($where_op);

        // 更改GET变量
        \think\Request::instance()->get(['filter'=>$tmp_fields]);
        \think\Request::instance()->get(['op'=>$tmp_op]);
        return $has_time;
    }

}
