<?php

namespace app\index\model;

use think\Model;
// use traits\model\SoftDelete;

class Logs extends Model
{
    //
    protected $table = 'logs';
    protected $createTime = 'log_datetime';
}
