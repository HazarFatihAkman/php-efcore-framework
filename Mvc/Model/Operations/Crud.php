<?php
namespace Model\Operations;

$crudBase =  dirname(__DIR__).'\Operations\Database.php';
include $crudBase;

class Crud extends \Model\Operations\Database{
    public function __construct()
    {
        parent::__construct();
    }

    public function Insert($Post,$Model){
        $nameSpace = new \ReflectionClass($Model);  
        $nameSpaceGet = $nameSpace->getNamespaceName()."\\";
        $table = strtolower(str_replace($nameSpaceGet,"",get_class($Model)));      
        
        foreach(array_keys($_POST) as $p){
                $Model->$p = $Post[$p];
        }

        $config = $Model->Config();        
        $count = count($config);

        $prepareStr = "INSERT INTO ".$table."(";
        $values = " Values(";
        $valuesParam = array();

        
        for($i = 0; $i < $count; $i++){
            $newCon = explode(".",$config[$i])[0];
            
            if($Model->$newCon != null){
                if($i == $count-1){
                    $prepareStr = $prepareStr.$newCon.") ";
                    $values = $values."?) ";
                    array_push($valuesParam,$Model->$newCon);
                }
                else{
                    $prepareStr = $prepareStr.$newCon.",";
                    $values = $values."?,";
                    array_push($valuesParam,$Model->$newCon);
                }
            }
            else if($Model->$newCon == null && $i == $count-1){
                $prepareStr = rtrim($prepareStr,",").") ";
                $values = rtrim($values,",").") ";
            }
        }
        $prepareStr = $prepareStr.$values;
        
        $stmt = $this->con->prepare($prepareStr);
        
        $this->DynamicBindVariables($stmt,$valuesParam);
        
        if($stmt->execute()){
            return true;
        }        
        else {
            return false;
        }
        // var_dump($this->con);
    }

    public function Read($Model){
        $nameSpace = new \ReflectionClass($Model);  
        $nameSpaceGet = $nameSpace->getNamespaceName()."\\";
        $table = strtolower(str_replace($nameSpaceGet,"",get_class($Model)));
        $newSql = "SELECT * FROM ".$table;
        $query = $this->con->query($newSql);       
        $read = array();
        
        if($query) {
            while($row = \mysqli_fetch_assoc($query)){
                $newModel = get_class($Model);
                $newModel = new $newModel();
                $rowKey = array_keys($row);
                foreach($rowKey as $readKey){                
                    if($row[$readKey] != null){
                        $newModel->$readKey = $row[$readKey];
                    }
                    else {
                        $newModel->$readKey = ' ';
                    }
                }
                array_push($read,$newModel);              
            }
            
            $newModel = get_class($Model);
            $newModel = new $newModel();
            // var_dump($newModel);
        }
        if($read == null){
            return array("Not Found"=>"Not Found.");
        }else {
            return $read;
        }
    }

    public function ReadSpecial($Post,$Model){
        $nameSpace = new \ReflectionClass($Model);  
        $nameSpaceGet = $nameSpace->getNamespaceName()."\\";
        $table = strtolower(str_replace($nameSpaceGet,"",get_class($Model)));      
        foreach(array_keys($Post) as $p){
                $Model->$p = $Post[$p];
        }
        
        $prepareStr = "SELECT * FROM ".$table;
        $keys = array_keys($Post);

        $values = "";
        $valuesParam = array();
        
        $i = 1;
        foreach($keys as $key){
               if($i == count($keys)){
                    $values = $values." ".$key." = ? ";
                    array_push($valuesParam,$Model->$key);
                }
                else {
                    $values = $values." ".$key." = ? and ";  
                    array_push($valuesParam,$Model->$key);  
                }    
            $i++;
        }
        $prepareStr = $prepareStr." WHERE ".$values;
        
        $stmt = $this->con->prepare($prepareStr);
        
        $this->DynamicBindVariables($stmt,$valuesParam);
        
        $stmt->execute();  
        $read = $stmt->get_result();
               
        if($read->num_rows > 0){
            $read = $read->fetch_assoc();
            $readkey = array_keys((array)$read);

            foreach($readkey as $key){
                if($read[$key] != null){
                    $Model->$key = $read[$key];
                }
                else{
                    $Model->$key= ' ';
                }
            }
        
            return $Model;
        }
        else {
            return array("Not Found"=>"Bulunamadı");
        }
        
    }

    public function Update($Post,$Model){
        $nameSpace = new \ReflectionClass($Model);  
        $nameSpaceGet = $nameSpace->getNamespaceName()."\\";
        $table = strtolower(str_replace($nameSpaceGet,"",get_class($Model)));
        $where = $Post['Where'];
        unset($Post['Where']);      
        foreach(array_keys($Post) as $p){
                $Model->$p = $Post[$p];
        }

        $prepareStr = "UPDATE ".$table." SET ";
        $keys = array_keys($Post);

        $values = "";
        $valuesParam = array();

        // var_dump($Model);

        // var_dump($keys);
        $i = 1;
        foreach($keys as $key){
            if($where != $key){
               if($i == count($keys)-1){
                    $values = $values." ".$key." = ? ";
                    array_push($valuesParam,$Model->$key);
                }
                else {
                    $values = $values." ".$key." = ?, ";  
                    array_push($valuesParam,$Model->$key);  
                }
            }    
            $i++;
        }
        array_push($valuesParam,$Model->$where);
        $prepareStr = $prepareStr.$values." WHERE ".$where." = ?";
        $stmt = $this->con->prepare($prepareStr);
        
        $this->DynamicBindVariables($stmt,$valuesParam);
        if($stmt->execute()){
            return true;
        }        
        else {
            return false;
        }        
       
    }

    public function Delete($Post,$Model){        
        $nameSpace = new \ReflectionClass($Model);  
        $nameSpaceGet = $nameSpace->getNamespaceName()."\\";
        $table = strtolower(str_replace($nameSpaceGet,"",get_class($Model)));
        $where = $Post["Where"];      
        unset($Post["Where"]);
        foreach(array_keys($Post) as $p){
                $Model->$p = $Post[$p];
        }

        $prepareStr = "DELETE FROM ";
        $keys = array_keys($Post);

        $values = "";
        $valuesParam = array();
        
        if(method_exists($Model,"Child")){
            $Childrens = $Model->Child();
            $keyChildrens = array_keys($Childrens);
            foreach($keyChildrens as $key){
                $ModelChild = $key;
                $ChildTable = strtolower($ModelChild);
                
                $prepareStr2 = $prepareStr.$ChildTable." WHERE ";
                
                $Childkey = array_keys($Childrens[$ModelChild]);
                $i = 0;
                foreach($Childkey as $ch){
                    if($i == count($Childkey)-1){
                        $values = $values." ".$ch." = ?";
                        $Key = $Childrens[$ModelChild][$ch];
                        array_push($valuesParam,$Model->$Key);
                    }
                    else{
                        $values = $values." ".$ch." = ?, ";
                        $Key = $Childrens[$ModelChild][$ch];
                        array_push($valuesParam,$Model->$Key);
                    }
                    $i++;
                }
                
                // echo $prepareStr.$values."<br>";
                // var_dump($valuesParam);
                $prepareStr2 = $prepareStr2.$values;
                $stmt2 = $this->con->prepare($prepareStr2);
        
                $this->DynamicBindVariables($stmt2,$valuesParam);
        
                $stmt2->execute(); 
            } 
        }
        $valuesParam = array();
        
        array_push($valuesParam,$Model->$where);
       
        $prepareStr = $prepareStr.$table." WHERE ".$where." = ?";
        
        $stmt = $this->con->prepare($prepareStr);
        $this->DynamicBindVariables($stmt,$valuesParam);

        if($stmt->execute()){
            return true;
        }        
        else {
            return false;
        }        
        
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}