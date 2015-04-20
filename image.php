<?php
function isImage($file){
    $ext = substr($file,strpos($file,"."));
    if(strtoupper($ext)==".JPEG" || strtoupper($ext)==".JPG" || strtoupper($ext)==".BMP" || strtoupper($ext)==".PNG" || strtoupper($ext)==".GIF"){
        return true;
    }
    else return false;
}
?>