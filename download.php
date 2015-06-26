<?php
$dir = $_GET['file_dir'];
$name = $_GET['file_name'];
$file = $dir.$name;
outputFile($file,"test.txt",'');

/*function download($file_dir,$file_name){
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
}*/

function outputFile($file, $name, $mime_type='') {
    $fileChunkSize = 1024*30;

    if(!is_readable($file)) die('File not found or inaccessible!');

    $size = filesize($file);
    $name = rawurldecode($name);

    if($mime_type=='')
    {
        $mime_type="application/force-download";
    }

    @ob_end_clean();

    if(ini_get('zlib.output_compression'))
        ini_set('zlib.output_compression', 'Off');

    header('Content-Type: ' . $mime_type);
    header('Content-Disposition: attachment; filename="'.$name.'"');
    header("Content-Transfer-Encoding: binary");
    header('Accept-Ranges: bytes');
    header("Cache-control: private");
    header('Pragma: private');
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

    if(isset($_SERVER['HTTP_RANGE']))
    {
        list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
        list($range) = explode(",",$range,2);
        list($range, $range_end) = explode("-", $range);
        $range=intval($range);
        if(!$range_end)
            $range_end=$size-1;
        else
            $range_end=intval($range_end);

        $new_length = $range_end-$range+1;
        header("HTTP/1.1 206 Partial Content");
        header("Content-Length: $new_length");
        header("Content-Range: bytes $range-$range_end/$size");
    }
    else
    {
        $new_length=$size;
        header("Content-Length: ".$size);
    }

    $chunksize = 1*($fileChunkSize);
    $bytes_send = 0;
    if ($file = fopen($file, 'r'))
    {
        if(isset($_SERVER['HTTP_RANGE']))
            fseek($file, $range);

        while(!feof($file) &&
            (!connection_aborted()) &&
            ($bytes_send<$new_length)
        )
        {
            $buffer = fread($file, $chunksize);
            print($buffer);
            flush();
            $bytes_send += strlen($buffer);
        }
        fclose($file);
    }
    else die('Error - can not open file.');

    die();
}
?>