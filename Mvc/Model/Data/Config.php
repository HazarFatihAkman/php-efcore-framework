<?php
namespace Model\Data;

class Config {
    public function GetDatabaseConfig(){
        return array('host' => 'localhost',
                     'user' => 'root',
                     'password' =>'',
                     'databaseName' =>'newFramework',
                     'charSet' =>'utf8',
                     'charSetconfig'=>'utf8mb4',
                     'Collate'=>'utf8mb4_turkish_ci'
                    );
    }
}