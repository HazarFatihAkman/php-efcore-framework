<?php
namespace Model\Data;

use Model\Operations\Database;

$dbContextBase =  dirname(__DIR__);
include $dbContextBase.'\Data\TestModel.php';
include $dbContextBase.'\Data\Model2.php';
include $dbContextBase.'\Data\TestLink.php';

class DbContext extends Database {
    public function __construct()
    {
        parent::__construct();
    }

    public function Start(){
        $TestModel = new \Model\Data\TestModel();
        $this->TableCreate($TestModel);

        $Model2 = new \Model\Data\Model2();
        $this->TableCreate($Model2);

        $TestLink = new \Model\Data\TestLink();
        $this->TableCreate($TestLink);
    }


    public function __destruct()
    {
        parent::__destruct();
    }
}