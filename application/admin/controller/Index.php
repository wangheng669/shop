<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function welcome()
    {
        return $this->fetch();
    }
    public function map()
    {
        return \Map::staticimage('山东省济南市商河县');
    }
}
