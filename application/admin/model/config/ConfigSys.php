<?php

namespace app\admin\model\config;

use think\Cache;
use think\Model;


class ConfigSys extends Model
{

    // 表名
    protected $table = 'config_sys';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];

    protected static function init()
    {
        self::beforeInsert(function ($row) {
            //清理缓存
            $key = "Config:ConfigSysList";
            Cache::store('redis')->rm($key);
            $key = "Config:ConfigSysKeyValue";
            Cache::store('redis')->rm($key);
        });

        self::afterUpdate(function ($row) {
            //清理缓存
            $key = "Config:ConfigSysList";
            Cache::store('redis')->rm($key);
            $key = "Config:ConfigSysKeyValue";
            Cache::store('redis')->rm($key);
        });

        self::afterDelete(function ($row) {
            //清理缓存
            $key = "Config:ConfigSysList";
            Cache::store('redis')->rm($key);
            $key = "Config:ConfigSysKeyValue";
            Cache::store('redis')->rm($key);
        });
    }


}
