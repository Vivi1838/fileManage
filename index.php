<?php
//define("DEFAULT_DIR" , "E:/week8");
define("DEFAULT_DIR" , "/Users/liujin834/work/vivi/fileManager");
if(!file_exists(DEFAULT_DIR))
    throw new \RuntimeException("Directory not set");
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>文件浏览</title>

    <!-- Bootstrap -->
    <link href="/lib/bootstrap-3.3.2-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="/lib/html5shiv.min.js"></script>
    <script src="/lib/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<h1 class="text-center">文件浏览</h1>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="/lib/jquery/jquery-1.11.2.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/lib/bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>

<div align="center">
    <table width="400" height="28" class="table table-bordered table-striped" align="center">
        <thead>
        <tr>
            <th width="80" height="15" class="text-center">文件名称</th>
            <th width="50" class="text-center">大小</th>
            <th width="80" class="text-center">创建时间</th>
            <th width="100" class="text-center">最后修改时间</th>
            <th width="50" class="text-center">操作</th>
        </tr>
        </thead>
        <?php
        include_once "./size.php";
        $php_self = $_SERVER['PHP_SELF'];          //获取当前脚本
        if(!isset($_GET['dir']) || empty($_GET['dir']))
            $dir = DEFAULT_DIR;                     //默认指定目录
        else
            $dir = $_GET['dir'];
        chdir($dir);                                 //改变当前目录
        $handle = opendir($dir);                     //打开目录
        while(false !== ($file = readdir($handle))) {         //循环读取目录中的目录和文件
            echo "<tr><td align='center' valign='middle'>";
            if (is_dir($file)) {                //判断是目录并且省略目录"."
                if ($file == ".")
                    echo "当前目录：" . getcwd() . "<br/>";
                elseif ($file == "..") {
                    $dir = getcwd() . DIRECTORY_SEPARATOR . "..";                   //上级目录
                    echo "<a href=$php_self?dir=$dir>上级目录</a><br/>";
                } else {
                    $dir = getcwd() . DIRECTORY_SEPARATOR . "$file";                 //子目录
                    echo "<a href=$php_self?dir=$dir>$file</a><br/>";
                }
            } else {
                $dir = getcwd() . DIRECTORY_SEPARATOR;
                $name = urlencode($file);
                $file = iconv("gb2312", "utf-8", $file);
                echo "<a href=download.php?file_dir=$dir&file_name=$name>$file</a><br/>";
            }
            if(is_dir($file)) {
                if($file == ".." || $file == '. ')
                    $file_size = "----";
                else
                    $file_size = round(directory_size(getcwd() . DIRECTORY_SEPARATOR . $file) / 1048576, 2) . "MB";
            }
            else {
                $file_size = round(filesize($file) / 1024);
                if($file_size < 1) $file_size = 1;
                $file_size .= "KB";
            }
            echo "<td align='center' valign='middle'>$file_size</td>";
            $create_time = date("y-m-d h:i:sA",filectime($file));
            echo "<td align='center' valign='middle'>$create_time</td>";
            $update_time = date("y-m-d h:i:sA",filemtime($file));
            echo "<td align='center' valign='middle'>$update_time</td>";
            if($file == "." || $file == "..") echo "<td align='center' valign='middle'>----</td>";
            else {
                $path = getcwd() . DIRECTORY_SEPARATOR . $file;
                echo "<td align='center' valign='middle'><a href=delete.php?dir=$path>删除</a></td>";
            }
        }
        closedir($handle);
        ?>
    </table>
</div>
</body>
</html>