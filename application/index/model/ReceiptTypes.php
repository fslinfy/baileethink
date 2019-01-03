<?php

namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class Receipttypes extends Model
{
    //
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    protected $table='receipt_types';
    protected $autoWriteTimestamp = 'datetime';
}
