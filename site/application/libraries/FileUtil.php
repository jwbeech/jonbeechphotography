<?php

class FileUtil
{
    public static function recursiveRemove($dir) {
        $structure = glob(rtrim($dir, "/").'/*');
        if (is_array($structure)) {
            foreach($structure as $file) {
                if (is_dir($file)) FileUtil::recursiveRemove($file);
                else if (is_file($file)) unlink($file);
            }
        }
        try{
            rmdir($dir);
        }
        catch(Exception $e){

        }
    }
}

?>