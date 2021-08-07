<?php
namespace app\ymy\controller;

use think\Controller;
use think\Config;

class Base extends Controller
{
    public $username;
    public function __construct()
    {
        parent::__construct();
        // var_dump($_COOKIE);
        // $this->username = $_SESSION['username'];
        $this->param = $this->request->param();
    }

    public function getName()
    {

    }
}