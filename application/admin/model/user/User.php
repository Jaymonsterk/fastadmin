<?php

namespace app\admin\model\user;

use app\admin\model\config\ConfigVip;
use app\common\model\Rediss;
use think\Config;
use think\Db;
use think\Model;

class User extends Model
{

    // 表名
    protected $table = 'user';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'isfistdeposit_text',
        'status_text',
        'ctime_text',
        'ltime_text',
        'utime_text'
    ];

    protected static function init()
    {
        self::beforeInsert(function ($row) {
            //处理密码
            if (!isset($row['passwd']) || !$row['passwd']) {
                return false;
            }else{
                $row['passwd'] = public_pw($row['passwd']);
            }
            if (!isset($row['paypasswd']) || !$row['paypasswd']) {
                return false;
            }else{
                $row['paypasswd'] = public_pw($row['paypasswd']);
            }
            //处理等级
            $row['vnum'] = $row['vnum']??0;
            $vip_list = get_vip_list();
            $row['vname'] = $vip_list[$row['vnum']];
            $vip = ConfigVip::order("vnum","asc")->where('vnum',$row['vnum'])->field("creditscore,vmonadmun")->find();
            $row['creditscore'] = $vip['creditscore']??0;//信用积分
            $row['vmonadmun'] = $vip['vmonadmun']??0;//做单次数
            //处理时间
            if (!isset($row['cip']) || !$row['cip']) {
                $row['cip'] = get_ip();
            }
            if (!isset($row['cdate']) || !$row['cdate']) {
                $row['cdate'] = date("Y-m-d H:i:s");
            }
            $row['ctime'] = time();
            //操作者
            $row['aid'] = session('admin.id');
            $row['aname'] = session('admin.username');
            $row['utime'] = time();
        });

        self::beforeUpdate(function ($row) {
            //处理密码
            if (!isset($row['passwd']) || !$row['passwd']) {
                $row['passwd'] = public_pw($row['passwd']);
            }else{
                unset($row['passwd']);
            }
            if (!isset($row['paypasswd']) || !$row['paypasswd']) {
                $row['paypasswd'] = public_pw($row['paypasswd']);
            }else{
                unset($row['paypasswd']);
            }
            //处理等级
            $row['vnum'] = $row['vnum']??0;
            $vip_list = get_vip_list();
            $row['vname'] = $vip_list[$row['vnum']];
            $vip = ConfigVip::order("vnum","asc")->where('vnum',$row['vnum'])->field("creditscore,vmonadmun")->find();
            $row['creditscore'] = $vip['creditscore']??0;//信用积分
            $row['vmonadmun'] = $vip['vmonadmun']??0;//做单次数
            //处理时间
            if (!isset($row['cip']) || !$row['cip']) {
                $row['cip'] = get_ip();
            }
            //操作者
            $row['aid'] = session('admin.id');
            $row['aname'] = session('admin.username');
            $row['utime'] = time();
        });

        self::afterInsert(function ($row){
            //处理关联表
            self::saveUserA($row);
        });

        self::afterUpdate(function ($row){
            //处理关联表
            self::saveUserA($row,true);
        });

        self::afterDelete(function ($row){
            $user_id = $row->id;
            //删除关联表中的数据
            UserA::destroy(['uid'=>$user_id]);
        });
    }

    //更新UserA关联表信息
    public static function saveUserA($row,$isUpdate=false){
        //更新关联表
        $upuid = (int)($row['upuid']);
        if($upuid > 0){
            //代理
            $dataA['upuname'] = self::where('id',$upuid)->value("uname");//上级用户名
            //如果上级，upuid=firstuid=0 大股东本身
            $up_user_info = self::getUpAndFirstUid($upuid);
            $dataA['firstuid']=$up_user_info['firstuid'];//大股东uid
            $dataA['firstuname']=$up_user_info['firstuname'];//大股东用户名
            $dataA['path']=$up_user_info['path'];//代理路径大股东默认为0
            $dataA['level'] = 0;//代理等级
        }else{
            //大股东
            $dataA['upuid'] = 0;//上级uid
            $dataA['upuname'] = "";//上级用户名
            $dataA['firstuid'] = 0;//大股东uid
            $dataA['firstuname'] = "";//大股东用户名
            $dataA['path'] = "";//代理路径大股东默认为0
            $dataA['level'] = 0;//代理等级
        }
        $dataA['uid'] = $row['id'];
        $dataA['uname'] = $row['uname'];
        $dataA['ctime'] = $row['ctime'];
        $dataA['cdate'] = $row['cdate'];
        $dataA['aid'] = $row['aid'];
        $dataA['aname'] = $row['aname'];
        $dataA['utime'] = $row['utime'];

        if($isUpdate){
            UserA::where('uid',$row->id)->data($dataA)->update();
        }else{
            UserA::create($dataA);
        }
    }

    public static function getUpAndFirstUid($upuid){
        $userinfo = (new UserA)->where(['uid'=>$upuid])->find();//上级用户
        $ret = [];
        if($userinfo['upuid'] == 0 && $userinfo['firstuid'] == 0){//上级是大股东
            $ret['firstuid'] = $userinfo['uid'];
            $ret['firstuname'] = $userinfo['uname'];
            $ret['path'] = $userinfo['uid'].",";
        }else{
            //如果是代理,则用户大股东跟代理的大股东一致
            $ret['firstuid'] = (int)$userinfo['firstuid'];
            $ret['firstuname'] = $userinfo['firstuname'];
            $ret['path'] = $userinfo['path'].$userinfo['uid'].",";
        }
        return $ret;
    }

    //用户资金操作
    //$type为 extra/money.php内user_money_log 对应的数据
    //$moneytype = money 用户金额  $moneytype = mortgage 用户押金
    public static function useaMoney($uid, $money,$type,$orderid='',$moneytype='money',$data=[])
    {
        if(!in_array($moneytype,['money','agent'])){
            return false;
        }

        if(empty($data)){
            $data = self::where("id",$uid)->find();
        }
        if($money > 0){
            self::where('id',$uid)->setInc($moneytype,$money);
        }else{
            self::where('id',$uid)->setDec($moneytype,abs($money));
        }
        $user_money_log = config('site.user_money_log');
        $type_arr = isset($user_money_log[$type])?$user_money_log[$type]:$user_money_log[0];
        $arr = [];
        $arr['uid']=$data['id'];
        $arr['uname']=$data['uname'];
        $arr['money']=$money;
        $arr['before']=$data['money'];
        $arr['after']=$data['money'] + $money;
        $arr['type']=$type;
        $arr['typename']=$user_money_log[$type];
        $arr['orderid']=$orderid;
        $arr['note']=$data['note']??"";
        $arr['ctime']=time();
        $arr['cdate']=date('Y-m-d H:i:s');
        self::inUserMoneyLog($arr);
        return true;
    }
    //插入日志
    public static function inUserMoneyLog($arr)
    {
        $UserMoneyLog = new UserMoneyLog($arr);
        $UserMoneyLog->save();
    }


    public function getIsfistdepositList()
    {
        return ['1' => __('Isfistdeposit 1'), '2' => __('Isfistdeposit 2')];
    }

    public function getStatusList()
    {
        return ['1' => __('Status 1'), '2' => __('Status 2'), '3' => __('Status 3')];
    }


    public function getIsfistdepositTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['isfistdeposit']) ? $data['isfistdeposit'] : '');
        $list = $this->getIsfistdepositList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getCtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['ctime']) ? $data['ctime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getLtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['ltime']) ? $data['ltime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getUtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['utime']) ? $data['utime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setCtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setLtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setUtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    public function usera()
    {
        return $this->belongsTo(UserA::class, 'id','uid',  [], 'LEFT')->setEagerlyType(0);
    }

}
