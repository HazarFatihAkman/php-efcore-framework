<?php
namespace Model\Data;

class TestModel {
    public int $Id = 0;
    public string $user;
    public string $pass;

    public function Config(){
        return array('Id.Int','user.Varchar(20)','pass.Varchar(32)');
    }

    public function Uniq(){
        return array('PRIMARY KEY(Id)','UNIQUE ( `user`, `pass`)');
    }
    public function Child(){
        return array("Model2"=>array("SubId"=>"Id"));
    }
}