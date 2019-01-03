<?php

namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class Questions extends Model
{
    //
    use SoftDelete;
    protected $deleteTime = 'delete_time';


    protected $autoWriteTimestamp = 'datetime';
}
