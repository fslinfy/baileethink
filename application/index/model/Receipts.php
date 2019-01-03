<?php

namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class Receipts extends Model
{
    //
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    protected $table = 'receipts';
    protected $autoWriteTimestamp = 'datetime';
}
