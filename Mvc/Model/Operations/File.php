<?php
namespace Model\Operations;

class File {
    public function FileCreate($FolderUrl,$html){
        $fileUrl = $FolderUrl."/index.php";
        $file = fopen($fileUrl,"w");
        fwrite($file,$html);
        fclose($file);
    }
    
    public function getDirContents($dir, &$results = array()) {
        $files = scandir($dir);
    
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = $path;
            } else if ($value != "." && $value != "..") {
                $this->getDirContents($path, $results);
                $results[] = $path;
            }
        }
    
        return $results;
    }

    public function ImageUpload($File,$FileUploadUrl){
        // var_dump($File);
        for($i = 0; $i < count($File['name']);$i++){
            if(strstr($File['type'][$i],"image/")){
                $fileName = $FileUploadUrl."/".$i.str_replace("image/",".",$File['type'][$i]);
                move_uploaded_file($File['tmp_name'][$i],$fileName);
            }
        }
    }

    public function ImageUpdate($File,$FileUploadUrl){
        // var_dump($File);
        for($i = 0; $i < count($File['name']);$i++){
            if(strstr($File['type'][$i],"image/")){
                move_uploaded_file($File['tmp_name'][$i],$FileUploadUrl);
            }
        }
    }

    
}