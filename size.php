<?php
function directory_size($dir){
    $directorySize = 0;
    if($dh = opendir($dir)) {
        while (($filename = readdir($dh)) != false) {
            if ($filename != '.' && $filename != '..') {
                if (is_file($dir . "/" . $filename))
                    $directorySize += filesize($dir . "/" . $filename);
                if (is_dir($dir . "/" . $filename))
                    $directorySize += directory_size($dir . "/" . $filename);
            }
        }
        closedir($dh);
        return $directorySize;
    }
}
?>