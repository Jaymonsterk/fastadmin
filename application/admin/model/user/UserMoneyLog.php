<?php

namespace app\admin\model\user;

use think\Model;


class UserMoneyLog extends Model
{
    // 表名
    protected $table = 'user_money_log';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = false;

}
