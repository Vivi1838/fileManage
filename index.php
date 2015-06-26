<?php
define("DEFAULT_DIR" , "E:/week8");
define("MB",1048576);
define("GB",1073741824);
//define("DEFAULT_DIR" , "/Users/liujin834/work/vivi/fileManager");
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
    <script src="/lib/jquery/jquery-1.11.2.min.js"></script>
    <script src="/lib/jquery/jquery.colorbox.js"></script>
    <script>
        $(document).ready(function(){
            //元素调用Colorbox的示例
            $(".group1").colorbox({ rel: 'group1' });
        });
    </script>
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
        function count_size($bit)
        {
            $type = array('Bytes','KB','MB','GB','TB');
            for($i = 0; abs($bit) >= 1024; $i++)//单位每增大1024，则单位数组向后移动一位表示相应的单位
            {
                $bit/=1024;
            }
            return abs(floor($bit*100)/100).$type[$i];//floor是取整函数，为了防止出现一串的小数，这里取了两位小数
        }

        include_once "./size.php";
        include_once "./image.php";
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
                if(isImage($file))
                    echo "<a href=pic.php?file_dir=$dir&file_name=$name>$file</a><br/>";
                else
                echo "<a href=download.php?file_dir=$dir&file_name=$name>$file</a><br/>";
            }
            if(is_dir($file)) {
                if($file == ".." || $file == '. ')
                    $file_size = "----";
                else $file_size = count_size(directory_size(getcwd() . DIRECTORY_SEPARATOR . $file));
            }
            else {
                $file_size = count_size(filesize($file));

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
        echo <<<END
        </tr>
        <tr>
        <td colspan="5" align="center">
            <form action="upload.php" method="post" enctype="multipart/form-data">
                需要保存的路径：<input type="text" name="path">
                <input type="hidden" name="MAX_FILE_SIZE" value="20480"/>
                <input type="file" name="uploadFile" size="25" maxlength="100"/>
                <input type="submit" value="上传">
            </form>
        </td>
        </tr>
END;
        closedir($handle);
 ?>
    </table>
</div>
</body>
</html>