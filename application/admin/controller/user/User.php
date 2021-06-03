<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\Validate;

/**
 * 用户管理
 *
 * @icon fa fa-user
 */
class User extends Backend
{
    
    /**
     * User模型对象
     * @var \app\admin\model\user\User
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\user\User;
        $this->view->assign("isfistdepositList", $this->model->getIsfistdepositList());
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign('vip_list',get_vip_list());
        $this->assignconfig('vip_list',get_vip_list());
    }

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            //1.0 查找代理下线
            $list = $this->model
                ->with(['usera'])
                ->where($where)
                ->order($sort, $order)
                ->paginate($limit);

            foreach ($list as $row) {
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 用户加减款
     */
    public function recharge($ids = null){
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->post("row/a");
            //判断金额
            if (!$params['amount']) {
                $this->error(__("Please input correct amount"));
            }

            if (!Validate::is($params['amount'], 'number')) {
                $this->error(__("Please input correct amount"));
            }
            $orderid = date("YmdHis");//存放备注

            $data = array_merge($row->getData(),$params);
            Db::startTrans();
            try {
                //判断增减 写入余额 写入资金日志 人工加减款 type=11
                if ($params['action'] == '1') {
                    //加钱
                    $money = $params['amount'];
                }else{
                    //减钱
                    $money = -1*$params['amount'];
                }
                \app\admin\model\user\User::useaMoney($ids, $money,1,$orderid,'money',$data);

                Db::commit();
                $this->success();
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }

        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

}
