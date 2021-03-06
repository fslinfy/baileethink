<?php

namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class Students extends Model
{
    //
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    
    protected $table = 'students';
    protected $autoWriteTimestamp = 'datetime';
}
