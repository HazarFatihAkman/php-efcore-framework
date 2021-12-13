<?php

use Model\Data\Model2;

echo "<pre>";
$baseIndex = __DIR__;

include $baseIndex.'/Mvc/Model/Operations/Crud.php';
include $baseIndex.'/Mvc/Model/Data/DbContext.php';
include $baseIndex.'/Mvc/Model/Operations/Core.php';

$DbContext = new \Model\Data\DbContext();
$TestModel = new Model\Data\TestModel();
$Model2 = new Model\Data\Model2();
$Crud = new Model\Operations\Crud();
$Core = new Model\Operations\Core();
$TestLink = new \Model\Data\TestLink();

$DbContext->Start();

$read = $Crud->Read($TestLink);


echo "</pre>";
?>
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
  </head>

  <body>

    <script>
      function showHint(str) {
        if (str.length == 0) {
          document.getElementById("navigation_link").value = "";
          document.getElementById("CreateLink").value = "";
          return;
        } else {
          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
              document.getElementById("navigation_link").value = "/" + this.responseText;
              document.getElementById("CreateLink").value = "/" + this.responseText;
            }
          };
          xmlhttp.open("GET", "Ajax/CreateLink.php?Title=" + str, true);
          xmlhttp.send();
        }
      }
      function showHint2(str) {
        if (str.length == 0) {
          document.getElementById("navigation_link2").value = "";
          document.getElementById("NewLink2").value = "";
          return;
        } else {
          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
              document.getElementById("navigation_link2").value = "/" + this.responseText;
              document.getElementById("NewLink2").value = "/" + this.responseText;
            }
          };
          xmlhttp.open("GET", "Ajax/CreateLink.php?Title=" + str, true);
          xmlhttp.send();
        }
      }
    </script>
    <form action="Mvc\Controller\Insert.php" method="POST">
      <input type="text" name="user" placeholder="user">
      <input type="password" name="pass" placeholder="pass">
      <input type="text" hidden name="Model" value="TestModel">
      <input type="submit">
    </form>
    <form action="Mvc\Controller\Insert.php" enctype="multipart/form-data" method="POST">
      <input type="text" onkeyup="showHint(this.value)" id="Title" name="Title" placeholder="Title">
      <input type="text" name="Link" id="navigation_link">
      <input type="text" name="CreateLink" id="CreateLink">
      <input type="text" hidden name="Model" value="TestLink">
      <input type="file" name="Img[]" multiple>
      <input type="submit">
    </form>
    <table>
      <?php
          if(empty($read["Not Found"])){            
            $readKey = array_keys((array)$read[0]);
          }
          else {
            $readKey = array();
            array_push($readKey,'Table Header');
          }
          
          
          if(empty($read["Not Found"])){ 
            echo '<tr>
              <form action="Mvc\Controller\Delete.php" method="POST">';
              foreach($readKey as $rKey){
                echo "<td>".$rKey."</td>";
              }
              echo "<td>Delete</td>";
            echo "</tr>";
          
            foreach($read as $childRead){
              echo "<tr>";
                foreach($childRead as $chldRead){
                  echo "<td>".$chldRead."</td>";
                }
                  echo "<td>
                        <button type='submit' name='Where' value='Id'>Delete</button>
                        <input value='TestLink' name='Model' hidden>     
                        <input name='CreateLink' type='text' hidden value='".$childRead->Link."'>
                        <input name='Id' type='text' hidden value='".$childRead->Id."'>
                      </td>
                      </form>
              </tr>";
            }
          }
          else {
            echo "<tr>";
              foreach($readKey as $rKey){
                echo "<td>".$rKey."</td>";
              }
            echo "</tr> 
                  <tr>
                    <td>".$read["Not Found"]."</td>
                  </tr>";
          }

        ?>
    </table>
    <br>
    <?php
      $_POST['Id'] = 45;
      $readSp = $Crud->ReadSpecial($_POST,$TestLink);
      unset($_POST['Id']);
      $getArray = (array)$readSp;
      if(empty($getArray["Not Found"])){
        ?>
     <form action="Mvc\Controller\Update.php" enctype="multipart/form-data"  method="POST">
      <input type="text" onkeyup="showHint2(this.value)" id="Title" name="Title" value="<?php echo $readSp->Title;?>" placeholder="Title">
      <input type="text" name="Link" value="<?php echo $readSp->Link;?>" id="navigation_link2">
      <input type="text" name="NewLink" value="<?php echo $readSp->Link;?>" id="NewLink2">
      <input type="text" name="OldLink" hidden value="<?php echo $readSp->Link;?>">
      <input type="text" name="Where" value="Id" hidden>
      <?php
        $glob = glob($baseIndex.$readSp->Link."/*");
        
        foreach($glob as $gl){
          if(!strstr($gl,".php") and !strstr($gl,".html")){
            echo '<div><img src="'.str_replace($baseIndex,"http://localhost/new-framework/",$gl).'" style="width:250px;height:auto; margin:5px;">
            <input type="file" name="Img[]">
            <button type="submit" value="'.$gl.'" name="ImgLink">Change</button></div>';
          }
        }
      ?>
      <input type="text" name="Id" value="<?php echo $readSp->Id;?>" hidden>
      <input type="text" hidden name="Model" value="TestLink">
      <input type="submit">
    </form>
        <?php
      }
      ?>

  </body>

</html>