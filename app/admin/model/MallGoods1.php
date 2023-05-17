<?php

// +----------------------------------------------------------------------
// | EasyAdmin
// +----------------------------------------------------------------------
// | PHP交流群: 763822524
// +----------------------------------------------------------------------
// | 开源协议  https://mit-license.org 
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zhongshaofa/EasyAdmin
// +----------------------------------------------------------------------

namespace app\admin\model;


use app\common\model\TimeModel;

class MallGoods1 extends TimeModel
{

    protected $table = "ea_mall_goods6";

    protected $deleteTime = 'delete_time';

    public function cate()
    {
        return $this->belongsTo('app\admin\model\MallCate', 'cate_id', 'id');
    }

}