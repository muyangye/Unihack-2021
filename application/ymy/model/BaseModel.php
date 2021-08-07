<?php
namespace app\ymy\model;

use think\Db;
use think\Model;

class BaseModel
{
    public $db;
    public function __construct()
    {
        $this->db = Db::connect();
    }
}