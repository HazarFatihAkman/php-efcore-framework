<?php
 namespace Model\Operations;

 class Core {
    protected $Dict = [
        "ü"=>"u",
        "ö"=>"o",
        "ı"=>"i",
        "ç"=>"c",
        " "=>"-",
        "ğ"=>"g",
        "ş"=>"s",
        "?"=>"",
        "'"=>"",
        "!"=>"",
        "&"=>"",
        " "=>"-",
        "?"=>"",
        "\\"=>"",
        "*"=>"",
        "("=>"",
        ")"=>"",
        "&"=>"",
        "%"=>"",
        "!"=>"",
        "'"=>"",
        "^"=>"",
        "#"=>"",
        "#"=>"$",
        ","=>"",
        "."=>"",
        "+"=>"",
        "{"=>"",
        "}"=>"",
        "["=>"",
        "]"=>"",
        "_"=>"",
        "'"=>"",
        '"'=>""
        ];
        
    public function toLowerUrl($value){
        $value = mb_strtolower($value);
        
        $newStr = '';
        $value = mb_str_split($value);
        foreach($value as $v){
            if(isset($this->Dict[$v])){
                $newStr = $newStr.$this->Dict[$v];
            }
            else {
                $newStr = $newStr.$v;
            }
        }    
        return $newStr;    
    }

    public function ModelValuePlacement($Model,$Post){
        $Post_keys = array_keys($Post);
        foreach($Post_keys as $p){
            $Model->$p = $Post[$p];
        }
        return $Model;
    }

    public function NotFoundModel($Model){
        $ModelKey = array();
        foreach($Model->Config() as $config){
            $config = explode(".",$config)[0];
            array_push($ModelKey,$config);
        }
        var_dump($ModelKey);
        foreach($ModelKey as $model){
            $Model->$model = "Not Found";    
        }

        return $Model;
    }
 }