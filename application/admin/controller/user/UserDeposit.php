<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;
use think\Db;
use think\exception\PDOException;

/**
 * 用户充值管理
 *
 * @icon fa fa-circle-o
 */
class UserDeposit extends Backend
{
    
    /**
     * UserDeposit模型对象
     * @var \app\admin\model\user\UserDeposit
     */
    protected $model = null;
    protected $enableValue = ['1','2','3'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\user\UserDeposit;
        $this->view->assign("statusList", $this->model->getStatusList());
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

    //处理充值
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
                    $ret['msg'] = "禁止修改到此状态23-2";
                }else{
                    //充值成功 修改状态 充值用户余额
                    \app\admin\model\user\User::useaMoney($row['uid'], $row['money'],2,$row['orderid']);
                    $ret['continue'] = true;
               }
                break;
            case 3:
                if($old_status >=2){
                    $ret['code'] = 109;
                    $ret['msg'] = "禁止修改到此状态23-3";
                }else{
                    $ret['continue'] = true;
                }
                break;
            default:
                $ret['code'] = 109;
                $ret['msg'] = "充值失败";
                break;
        }
        return $ret;
    }

}
