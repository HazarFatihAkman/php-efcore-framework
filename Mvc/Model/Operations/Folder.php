<?php
namespace Model\Operations;


$FileBase =  dirname(__DIR__);
include $FileBase.'\Operations\File.php';
class Folder extends \Model\Operations\File{

    public function CreatFolder($folderUrl,$html){
        if(!file_exists($folderUrl)){
            mkdir($folderUrl);
            $this->FileCreate($folderUrl,$html);
        }        
    }

    public function RenameFolder($folderUrl,$newFolder,$html){
        rename($folderUrl,$newFolder);
        $this->FileCreate($newFolder,$html);
    } 
    
    public function DeleteFolder($folderUrl){
        $folders = $this->getDirContents($folderUrl);

        foreach($folders as $folder){
            if(strstr($folder,".")){
                unlink($folder);
            }
            else {
                rmdir($folder);
            }
            //  echo $folder."<br>";
        }

        rmdir($folderUrl);
    }
    
    
}