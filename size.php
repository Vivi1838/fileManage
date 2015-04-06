<?php
function directory_size($dir){
    $directorySize = 0;
    if($dh = opendir($dir)) {
        while (($filename = readdir($dh)) != false) {
            if ($filename != '.' && $filename != '..') {
                if (is_file($dir . PATH_SEPARATOR . $filename))
                    $directorySize += filesize($dir . PATH_SEPARATOR . $filename);
                if (is_dir($dir . PATH_SEPARATOR . $filename))
                    $directorySize += directory_size($dir . PATH_SEPARATOR . $filename);
            }
        }
        closedir($dh);
        return $directorySize;
    }
}