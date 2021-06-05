<?php

namespace app\admin\controller\report;

use app\admin\model\Admin;
use app\admin\model\user\User;
use app\common\controller\Backend;
use app\common\model\Attachment;
use fast\Date;
use think\Db;

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

    /**
     * 数总览
     * @return void
     */
    public function overview()
    {
        //今天
        $date = date("Y-m-d");
        $today = \app\admin\model\report\ReportUserDay::where('dates',$date)
            ->field("sum(isnew) as new_user,sum(depositnum) as buy_user")
            ->field("sum(depositmoney) as deposit,sum(withdrawalsmoney) as withdraw,sum(yebmoney) as yuebao")
            ->find();
        //今日新增用户
        $today_user = \app\admin\model\report\ReportAgencyDay::where('dates',$date)
            ->where('upuid',"=",0)
            ->field("sum(isnew) as new_user")
            ->find();
        //昨天
        $yesterday_date = date("Y-m-d",strtotime("-1 day"));
        $yesterday = \app\admin\model\report\ReportUserDay::where('dates',$yesterday_date)
            ->field("sum(isnew) as new_user,sum(isfistdeposit) as buy_user")
            ->field("sum(depositmoney) as deposit,sum(withdrawalsmoney) as withdraw,sum(yebmoney) as yuebao")
            ->find();
        //昨天新增用户
        $yesterday_user = \app\admin\model\report\ReportAgencyDay::where('dates',$yesterday_date)
            ->where('upuid',"=",0)
            ->field("sum(isnew) as new_user")
            ->find();
        //总量
        $total = \app\admin\model\report\ReportAgencyDay::where(true)
            ->where('upuid',"=",0)
            ->field("sum(isnew) as new_user,sum(isfistdeposit) as buy_user")
            ->field("sum(depositmoney) as deposit,sum(withdrawalsmoney) as withdraw,sum(yebmoney) as yuebao")
            ->find();
        $total_shop = \app\admin\model\config\ConfigShop::where(true)->count();
        //余额宝总额
        $total_user_yuebao = \app\admin\model\user\User::where(true)->sum("yebmoney");
        //今日余额宝
        $today_yuebao = \app\admin\model\order\OrderUserYuebao::where('ctime','>=',strtotime(date("Y-m-d 00:00:00")))->sum("setmoney");

        //当前用户总数量
        $total_user = \app\admin\model\user\User::where(true)->count('id');
        //当前商品总数量
        $total_shop = $total_shop;
        //今日总充值
        $today_total_deposit = (int)$today['deposit'];
        //历史总充值
        $total_deposit = (int)$total['deposit'];
        //历史总提现
        $total_withdraw = (int)$total['withdraw'];
        //今日新增用户数
        $today_new_user = (int)$today_user['new_user'];
        //今日充值用户数
        $today_deposit_user = \app\admin\model\report\ReportUserDay::where('dates',$date)->where('depositmoney','>',0)->count('id');
        //今日下单用户数 做单数大于0
        $today_buy_user = \app\admin\model\report\ReportUserDay::where('dates',$date)->where('totalnum','>',0)->count('id');
        //昨日新增用户数
        $yesterday_new_user = (int)$yesterday_user['new_user'];
        //昨日下单用户数
        $yesterday_buy_user = \app\admin\model\report\ReportUserDay::where('dates',$yesterday_date)->where('totalnum','>',0)->count('id');
        //今日余额宝存入总额
        $today_yuebao = $today_yuebao;
        //余额宝总额
        $total_yuebao = $total_user_yuebao;

        //提现今日
        $starttime_withdraw = Date::unixtime('day', 0);
        $endtime_withdraw = Date::unixtime('day', 0, 'end');
        //今日提现用户数
        $today_withdraw_user = Db("user_withdrawal")->where('ctime', 'between time', [$starttime_withdraw, $endtime_withdraw])
            ->distinct('uid')
            ->where("status",3)
            ->count();
        //今日总提现
        $today_total_withdraw = Db("user_withdrawal")->where('ctime', 'between time', [$starttime_withdraw, $endtime_withdraw])
            ->field('sum(totalmoney) AS totalmoney')
            ->where("status",3)
            ->value('totalmoney');

        //提现昨日
        $starttime_withdraw = Date::unixtime('day', -1);
        $endtime_withdraw = Date::unixtime('day', -1, 'end');
        //昨日提现用户数
        $yesterday_withdraw_user = Db("user_withdrawal")->where('ctime', 'between time', [$starttime_withdraw, $endtime_withdraw])
            ->distinct('uid')
            ->where("status",3)
            ->count();
        //昨日总提现
        $yesterday_withdraw_money = Db("user_withdrawal")->where('ctime', 'between time', [$starttime_withdraw, $endtime_withdraw])
            ->field('sum(totalmoney) AS totalmoney')
            ->where("status",3)
            ->value('totalmoney');

        $data = [
            'total_user'=>$total_user,
            'total_shop'=>$total_shop,
            'today_total_deposit'=>$today_total_deposit,
            'total_deposit'=>$total_deposit,
            'today_total_withdraw'=>(int)$today_total_withdraw,
            'total_withdraw'=>$total_withdraw,
            'today_new_user'=>$today_new_user,
            'today_deposit_user'=>$today_deposit_user,
            'today_withdraw_user'=>(int)$today_withdraw_user,
            'today_buy_user'=>$today_buy_user,
            'yesterday_new_user'=>$yesterday_new_user,
            'yesterday_buy_user'=>$yesterday_buy_user,
            'today_yuebao'=>$today_yuebao,
            'total_yuebao'=>$total_yuebao,
            'yesterday_withdraw_user'=>(int)$yesterday_withdraw_user,
            'yesterday_withdraw_money'=>(int)$yesterday_withdraw_money,
        ];
        $this->assign($data);

        try {
            \think\Db::execute("SET @@sql_mode='';");
        } catch (\Exception $e) {

        }
        $column = [];
        $starttime = Date::unixtime('day', -6);
        $endtime = Date::unixtime('day', 0, 'end');
        $joinlist = Db("user")->where('ctime', 'between time', [$starttime, $endtime])
            ->field('ctime, status, COUNT(*) AS nums, DATE_FORMAT(FROM_UNIXTIME(ctime), "%Y-%m-%d") AS join_date')
            ->group('join_date')
            ->select();
        for ($time = $starttime; $time <= $endtime;) {
            $column[] = date("Y-m-d", $time);
            $time += 86400;
        }
        $userlist = array_fill_keys($column, 0);
        foreach ($joinlist as $k => $v) {
            $userlist[$v['join_date']] = $v['nums'];
        }

        $dbTableList = Db::query("SHOW TABLE STATUS");
        $this->view->assign([
            'totaluser'       => $total_user,
//            'totaladdon'      => count(get_addon_list()),
//            'totaladmin'      => Admin::count(),
//            'totalcategory'   => 0,//\app\common\model\Category::count(),
            'sevenuserlogin' => User::whereTime('ltime', '-3 days')->count(),
            'todayuserlogin'  => User::whereTime('ltime', 'today')->count(),
            'sevendau'        => User::whereTime('ctime|ltime|utime', '-7 days')->count(),
            'thirtydau'       => User::whereTime('ctime|ltime|utime', '-30 days')->count(),
            'threednu'        => User::whereTime('ctime', '-3 days')->count(),
            'sevendnu'        => User::whereTime('ctime', '-7 days')->count(),
//            'dbtablenums'     => count($dbTableList),
//            'dbsize'          => array_sum(array_map(function ($item) {
//                return $item['Data_length'] + $item['Index_length'];
//            }, $dbTableList)),
//            'attachmentnums'  => Attachment::count(),
//            'attachmentsize'  => Attachment::sum('filesize'),
//            'picturenums'     => Attachment::where('mimetype', 'like', 'image/%')->count(),
//            'picturesize'     => Attachment::where('mimetype', 'like', 'image/%')->sum('filesize'),
        ]);

        $this->assignconfig('column', array_keys($userlist));
        $this->assignconfig('userdata', array_values($userlist));


        return $this->view->fetch();
    }

    /**
     * 充值日报表
     * @return string|\think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function recharge()
    {
        //当前是否为关联查询
        $this->relationSearch = false;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {

            $has_time = $this->params();
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $field = "uid,uname,id,dates,times,ctime, ";
            $field .= " sum(`depositmoney`) as depositmoney, sum(`depositnum`) as depositnum ";

            $list = $this->model
                ->field($field)
                ->where($where)
                ->where('depositmoney','>',0)
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
     * 提现日报表
     */
    public function withdraw()
    {
        //当前是否为关联查询
        $this->relationSearch = false;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            $has_time = $this->params("withdraw");
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $field = "uid,uname,id,dates,times,ctime, ";
            $field .= " sum(`withdrawalsmoney`) as withdrawalsmoney, sum(`withdrawalsnum`) as withdrawalsnum ";

            $list = $this->model
                ->where($where)
                ->where('withdrawalsmoney','>',0)
                ->field($field)
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
     * 任务日报表
     */
    public function task()
    {
        //当前是否为关联查询
        $this->relationSearch = false;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            $has_time = $this->params("task");
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            //$where[] = ['totalnum|commission','>',0];
            $field = "uid,uname,id,dates,times,ctime, ";
            $field .= " sum(`totalnum`) as totalnum, sum(`successnum`) as successnum, sum(`failnum`) as failnum, ";
            $field .= " sum(`usemoney`) as usemoney, sum(`reward`) as reward, sum(`commission`) as commission ";

            $list = $this->model
                ->field($field)
                ->where($where)
                ->where('totalnum|commission','>',0)
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
    protected function params($action = "index"): bool
    {
        $today = date("Y-m-d");
        $today_range = $today." 00:00:00 - ".$today." 23:59:59";
        $time_op = "RANGE";

        //字段
        $tmp_fields = $this->request->get("filter", '');
        $where_fields = (array)json_decode($tmp_fields, true);
        $has_time = true;
        if(!isset($where_fields['times']) || empty($where_fields['times'])){
            $has_time = false;
            $where_fields['times'] = $today_range;
        }

        //操作符
        $tmp_op = $this->request->get("op", '');
        $where_op = (array)json_decode($tmp_op, true);
        if(!isset($where_op['times']) || empty($where_op['times'])){
            $where_op['times'] = $time_op;
        }

        if($action == "withdraw") {
//            if(!isset($where_fields['withdrawalsmoney']) || empty($where_fields['withdrawalsmoney'])){
//                $where_fields['withdrawalsmoney'] = 0;
//            }
//            //操作符
//            $where_op = (array)json_decode($tmp_op, true);
//            if(!isset($where_op['withdrawalsmoney']) || empty($where_op['withdrawalsmoney'])){
//                $where_op['withdrawalsmoney'] = ">";
//            }
        }

        if($action == "task") {
//            if(!isset($where_fields['totalnum']) || empty($where_fields['totalnum'])){
//                $where_fields['totalnum'] = 0;
//            }
//            //操作符
//            $where_op = (array)json_decode($tmp_op, true);
//            if(!isset($where_op['totalnum']) || empty($where_op['totalnum'])){
//                $where_op['totalnum'] = ">";
//            }
        }

        $tmp_fields = json_encode($where_fields);
        $tmp_op = json_encode($where_op);

        // 更改GET变量
        \think\Request::instance()->get(['filter'=>$tmp_fields]);
        \think\Request::instance()->get(['op'=>$tmp_op]);
        return $has_time;
    }

}
