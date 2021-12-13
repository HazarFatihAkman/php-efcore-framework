<?php
namespace Model\Data;

class Model2 {
    public int $Id = 0;
    public int $SubId;
    public string $name;

    public function Config(){
        return array('Id.Int','SubId.Int','name.Varchar(32)');
    }

    public function Uniq(){
        return array('PRIMARY KEY(Id)','UNIQUE ( `SubId`, `name`)');
    }
    
}