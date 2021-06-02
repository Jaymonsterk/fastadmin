<?php

namespace app\admin\model\config;

use think\Cache;
use think\Model;


class ConfigActivity extends Model
{

    // 表名
    protected $table = 'config_activity';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'status_text',
        'utime_text'
    ];

    protected static function init()
    {
        self::beforeInsert(function ($row) {
            //操作者
            if (!isset($row['aid']) || !$row['aid']) {
                $row['aid'] = session('admin.id');
            }
            if (!isset($row['aname']) || !$row['aname']) {
                $row['aname'] = session('admin.username');
            }
            if (!isset($row['utime']) || !$row['utime']) {
                $row['utime'] = time();
            }
            //清理缓存
            $key = "Config:ConfigActivity";
            Cache::store('redis')->rm($key);
        });

        self::beforeUpdate(function ($row) {
            //操作者
            $row['aid'] = session('admin.id');
            $row['aname'] = session('admin.username');
            $row['utime'] = time();

            //清理缓存
            $key = "Config:ConfigActivity";
            Cache::store('redis')->rm($key);
        });

        self::afterDelete(function ($row) {
            //清理缓存
            $key = "Config:ConfigActivity";
            Cache::store('redis')->rm($key);
        });
    }

    public function getStatusList()
    {
        return ['1' => __('Status 1'), '2' => __('Status 2')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getUtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['utime']) ? $data['utime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setUtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
