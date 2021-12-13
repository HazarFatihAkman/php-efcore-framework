<?php

ob_start();
$base =  dirname(__DIR__);

include $base.'\Model\Operations\Crud.php';
include $base.'\Model\Data\DbContext.php';
include $base.'\Model\Operations\Core.php';
include $base.'\Model\Operations\Folder.php';
include $base.'\View\Components\PageComponents.php';
include $base.'\View\Components\Status.php';

// echo "<pre>";

$Core = new \Model\Operations\Core();
$Crud = new \Model\Operations\Crud();
$PageComponents = new \View\Components\PageComponents();
$Folder = new \Model\Operations\Folder();
$Status = new \View\Components\Status();

$ModelName = "Model\\Data\\".$_POST['Model'];
$Model = new $ModelName();
$Model_Get = $_POST["Model"];
unset($_POST["Model"]);

if(!empty($_POST["CreateLink"])){
    $CreateLink = str_replace("\Mvc","",$base).$_POST["CreateLink"];
    unset($_POST["CreateLink"]);
}


if($Crud->Delete($_POST,$Model) == true){
    echo $Status->Success();

    if(method_exists($PageComponents,$Model_Get)){
        $newModel = new $ModelName();
        $Convert = $Core->ModelValuePlacement($newModel,$_POST);
        $Folder->DeleteFolder($CreateLink);
        unset($Convert);
    }
}
else {
    echo $Status->Failed();
}

header("refresh:3;url=".$_SERVER['HTTP_REFERER']);
ob_end_flush();
?>