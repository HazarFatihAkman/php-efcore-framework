<?php
namespace Model\Data;

class TestLink {
    public int $Id = 0;
    public string $Title;
    public string $Link;

    
    public function Config(){
        return array('Id.Int','Title.Varchar(150)','Link.Varchar(150)');
    }

    public function Uniq(){
        return array('PRIMARY KEY(Id)','UNIQUE (`Title`)');
    }
}