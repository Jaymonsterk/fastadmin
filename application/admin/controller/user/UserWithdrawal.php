<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;
use think\Db;
use think\exception\PDOException;

/**
 * 用户提现管理
 *
 * @icon fa fa-circle-o
 */
class UserWithdrawal extends Backend
{
    
    /**
     * UserWithdrawal模型对象
     * @var \app\admin\model\user\UserWithdrawal
     */
    protected $model = null;
    protected $enableValue = ['1','2','3','4'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\user\UserWithdrawal;
        $this->view->assign("statusList", $this->model->getStatusList());
    }

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = false;
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
                ->where($where)
                ->order($sort, $order)
                ->paginate($limit);

            foreach ($list as $row) {
                $json_arr = json_decode($row['sfjson'], true);
                $row['accountname'] = $json_arr['accountname'];
                $row['accountnum'] = $json_arr['accountnum'];
                $row['ifcscode'] = $json_arr['ifcscode'];
                $row['upi'] = $json_arr['upi'];
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 批量处理状态
     * @param null $ids
     */
    public function multi($ids = "")
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $ids = $ids ? $ids : $this->request->param("ids");
        if ($ids) {
            if ($this->request->has('params')) {
                parse_str($this->request->param("params"), $values);
                $values = $this->auth->isSuperAdmin() ? $values : array_intersect_key($values, array_flip(is_array($this->multiFields) ? $this->multiFields : explode(',', $this->multiFields)));
                if ($values) {
                    //判断字段值合法性
                    $status = $values[$this->multiFields];
                    if (!in_array($status, $this->enableValue)) {
                        $this->error('字段值不合法');
                    }
                    $adminIds = $this->getDataLimitAdminIds();
                    if (is_array($adminIds)) {
                        $this->model->where($this->dataLimitField, 'in', $adminIds);
                    }
                    $count = 0;
                    Db::startTrans();
                    try {
                        $list = $this->model->where($this->model->getPk(), 'in', $ids)->select();
                        foreach ($list as $index => $item) {
                            //处理状态
                            $ret = $this->handle($status,$item);
                            if(!$ret['continue']) { continue; }
                            $count += $item->allowField(true)->isUpdate(true)->save($values);
                        }
                        Db::commit();
                    } catch (PDOException $e) {
                        Db::rollback();
                        $this->error($e->getMessage());
                    } catch (Exception $e) {
                        Db::rollback();
                        $this->error($e->getMessage());
                    }
                    if ($count) {
                        $this->success();
                    } else {
                        $this->error(__('No rows were updated'));
                    }
                } else {
                    $this->error(__('You have no permission'));
                }
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    /**
     * 处理提现状态
     * @param null $ids
     */
    protected function handle($status,$row){
        $old_status = $row['status'];
        $status = (int)$status;
        $ret['continue'] = false;
        switch ($status){
            case 1:
                if($old_status == 1){
                    $ret['code'] = 109;
                    $ret['msg'] = "禁止修改到此状态1-1";
                }else{
                    $ret['code'] = 109;
                    $ret['msg'] = "不允许修改";
                }
                break;
            case 2:
                if($old_status >=2){
                    $ret['code'] = 109;
                    $ret['msg'] = "禁止修改到此状态34-2";
                }else{
                    //提交到三方
                    $sf_config = getSfById(2);
                    if(!$sf_config){
                        $ret['code'] = 123;
                        $ret['msg'] = "代付配置错误，请检查";
                    }else {
                        $data = json_decode($row['sfjson'], true);
                        $data['amount'] = sprintf("%0.2f",$row['money']);
                        $data['orderid'] = $row['orderid'];
                        $data['payname'] = $data['accountname'];
                        $data['paynumber'] = $data['accountnum'];
                        $data['ifsccode'] = $data['ifcscode'];
                        $data['notifyurl'] = $sf_config['df_notify_url'];
                        $ret = baxi_pay_df($sf_config, $data);
                        if(!is_array($ret)){
                            $ret = json_decode($ret,true);
//                            var_dump($ret);exit();
                            if($ret['code'] !== 0){
                                $ret['continue'] = false;
                            }else{
                                $ret['continue'] = true;
                            }
                        }else{
                            $ret['continue'] = true;
                        }
                    }
                }
                break;
            case 3:
                if($old_status >2){
                    $ret['code'] = 109;
                    $ret['msg'] = "禁止修改到此状态34-3";
                }else{
                    //成功 仅修改状态
                    $ret['continue'] = true;
                }
                break;
            case 4:
                if($old_status >2){
                    $ret['code'] = 109;
                    $ret['msg'] = "禁止修改到此状态34-4";
                }else{
                    //提现失败 修改状态 返还用户余额
                    \app\admin\model\user\User::useaMoney($row['uid'], $row['totalmoney'],3,$row['orderid']);
                    $ret['continue'] = true;
                }
                break;
            default:
                $ret['code'] = 109;
                $ret['msg'] = "退款失败";
                break;
        }
        return $ret;
    }
}
