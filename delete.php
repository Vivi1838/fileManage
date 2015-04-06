<?php

$dir = $_GET['dir'];
function deleteDirectory($dir)
{
    if (is_file($dir)) unlink($dir);
    else {
        if ($dh = opendir($dir)) {
            while (false != ($filename = readdir($dh))) {
                if ($filename != '.' && $filename != '..') {
                    if (is_dir($dir . "/" . $filename))
                        deleteDirectory(($dir . "/" . $filename));
                    if (is_file($dir . "/" . $filename))
                        unlink($dir . "/" . $filename);
                }
            }
            closedir($dh);
            rmdir($dir);
        }
    }
}
deleteDirectory($dir);
?>
<script language="JavaScript">
    history.go(-1);
</script>