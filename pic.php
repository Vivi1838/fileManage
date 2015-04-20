<?php
$dir = $_GET['file_dir'];
$file = $_GET['file_name'];
$image = file_get_contents($dir.$file);
header('Content-type: image/jpg');
echo $image;
?>