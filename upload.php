<?php

$path = $_POST['path'];
$uploadFile = $_FILES['uploadFile'];
$error = $uploadFile['error'];
switch($error){
    case 0:
        $uploadFileName = $uploadFile['name'];
        $uploadFileTmp = $uploadFile['tmp_name'];
        $destination = $path.$uploadFileName;
        move_uploaded_file($uploadFileTmp,$destination);
        echo <<<SCRIPT
<script>alert("上传成功！");
history.go(-1);</script>
SCRIPT;
        break;
    case 1:
        echo "文件大小超过php.ini中upload_max_filesize选项限制的值，上传失败！<br/>";
        break;
    case 2:
        echo "文件大小超过了FORM表单MAX_FILE_SIZE选项指定的值，上传失败！<br/>";
        break;
    case 3:
        echo "文件只有部分被上传！<br/>";
        break;
    case 4:
        echo "没有选择上传文件！<br/>";
        break;
}
?>