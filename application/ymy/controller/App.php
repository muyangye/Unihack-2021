<?php

namespace app\ymy\controller;

class App extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->fetch('index', [
            'menuTitle' => 'App',
            'subTitle' => 'index',
        ]);
    }
}