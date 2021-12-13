<?php
namespace Model\Operations;

use mysqli;
$databaseBase =  dirname(__DIR__);
include $databaseBase.'\Data\Config.php';

class Database extends \Model\Data\Config{
    protected $con,$DatabaseConfig;

    public function __construct(){
        $this->DatabaseConfig = $this->GetDatabaseConfig();

        $this->con = new mysqli(
           $this->DatabaseConfig["host"],
           $this->DatabaseConfig["user"],
           $this->DatabaseConfig["password"]          
        );
        
        $createSql = 'CREATE DATABASE '.$this->DatabaseConfig["databaseName"].' CHARACTER SET '.$this->DatabaseConfig["charSetconfig"].' COLLATE '.$this->DatabaseConfig["Collate"];

        if(mysqli_query($this->con, $createSql)) {
            echo "<br>Database Connection<br>";
            $this->con = new mysqli(
                $this->DatabaseConfig["host"],
                $this->DatabaseConfig["user"],
                $this->DatabaseConfig["password"],
                $this->DatabaseConfig["databaseName"]          
             );
        }
        else {
            $this->con = new mysqli(
                $this->DatabaseConfig["host"],
                $this->DatabaseConfig["user"],
                $this->DatabaseConfig["password"],
                $this->DatabaseConfig["databaseName"]          
             );            
        }     
        $this->con->set_charset($this->DatabaseConfig["charSet"]);
        unset($this->tmpCon);
    }

    public function TableCreate($Model){
        $nameSpace = new \ReflectionClass($Model);
        $nameSpaceGet = $nameSpace->getNamespaceName()."\\";
        $table = strtolower(str_replace($nameSpaceGet,"",get_class($Model)));

        $config = $Model->Config();
        $uniq = $Model->Uniq();

        $newStr = 'CREATE TABLE '.$table.' (';

        for($i=0; $i < count($config);$i++){
            $newCon = explode('.',$config[$i]);
            if($i == 0){                
                $newStr = $newStr.$newCon[0].' '.$newCon[1].' AUTO_INCREMENT, ';
            }
            else {
                $newStr = $newStr.$newCon[0].' '.$newCon[1].',';
            }

        }

        for($i=0; $i < count($uniq); $i++){            
            if($i == count($uniq)-1){
                $newStr = $newStr.' '.$uniq[$i].')';
            }
            else {                
                $newStr = $newStr.' '.$uniq[$i].',';
            }
        }
        
        
        if($this->con->query($newStr)){
            echo "<br>Database created successfully<br>";
        }
    }

    public function DynamicBindVariables($stmt, $params)
    {
        if ($params != null)
        {
            $types = '';
            foreach($params as $param)
            {
                if(is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } elseif (is_string($param)) {
                    $types .= 's';
                } else {
                    $types .= 'b';
                }
            }
      
            $bind_names[] = $types;
      
            for ($i=0; $i<count($params);$i++)
            {
                $bind_name = 'bind' . $i;
          
                $$bind_name = $params[$i];
          
                $bind_names[] = &$$bind_name;
            }
             
          
            // var_dump($bind_names);
            call_user_func_array(array($stmt,'bind_param'), $bind_names);
        }
        return $stmt;
    }

    public function TableUpdate($Model){
        $nameSpace = new \ReflectionClass($Model);
        $nameSpaceGet = $nameSpace->getNamespaceName()."\\";
        $table = strtolower(str_replace($nameSpaceGet,"",get_class($Model)));
        echo "<pre>";
        $config = $Model->Config();
        $uniq = $Model->Uniq();
        $sql = "SHOW COLUMNS FROM ".$table;
        $result = mysqli_query($this->con,$sql);
        
        $oldColumns = array();
        $configColumns = array();
        
        $deleteColumns = array();
        $addColumns = array();

        while($row = mysqli_fetch_array($result)){
            array_push($oldColumns,$row['Field']);
        }

        foreach($config as $conf){
            array_push($configColumns,explode('.',$conf)[0]);
        }

        $oldColumnsCount = count($oldColumns);
        $configColumnsCount = count($configColumns);

        switch($oldColumnsCount){
            case ($oldColumnsCount < $configColumnsCount):
                
                echo "Old : ".$oldColumnsCount." < ".$configColumnsCount." : Config<br>";
                echo "< <br>";
                break;
            case ($oldColumnsCount > $configColumnsCount):
                
                echo "Old : ".$oldColumnsCount." > ".$configColumnsCount." : Config<br>";
                echo "> <br>";
                break;
            case ($oldColumnsCount == $configColumnsCount):
                
                echo "Old : ".$oldColumnsCount." = ".$configColumnsCount." : Config<br>";
                echo "= <br>";
                break;                    
        }

        //ayrıştırma yapılacak

        var_dump($deleteColumns);
        var_dump($addColumns);

        $deleteStr = 'ALTER TABLE '.$table.' DROP ';

        $newStr = 'ALTER TABLE '.$table.' ADD ';
        // var_dump($oldColumns);
        echo "</pre>";
    }

    public function __destruct(){
        mysqli_close($this->con);
    }
}