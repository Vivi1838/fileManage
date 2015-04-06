<?php
$dir = $_GET['file_dir'];
$name = $_GET['file_name'];
download($dir,$name);
function download($file_dir,$file_name){
    if(!file_exists($file_dir.$file_name))                     //检查文件是否存在
        exit ("文件不存在或已删除");
    else{
        $file = fopen($file_dir.$file_name,"r");               //打开文件
        header("Content-Type: application/octet-stream");
        header("Content-disposition: attachment; filename=\"".$file_name."\""); ;
        header("Content-Length: ".filesize($file_dir.$file_name));
        //输出文件内容
        echo fread($file,filesize($file_dir.$file_name));
        fclose($file);
        exit;
    }
}
?>