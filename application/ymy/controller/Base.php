<?php
namespace app\ymy\controller;

use think\Controller;
use think\Config;

class Base extends Controller
{
    public $name;
    public function __construct()
    {
        parent::__construct();
        $this->name = 'USC';
        $this->param = $this->request->param();
    }

    public function getUSCName()
    {
        print_r($this->param);
        return ;
    }
}