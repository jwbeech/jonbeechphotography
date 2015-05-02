<?php

class StringUtil
{
    public static function padNumber($cnt, $totalLen = 5){
        $result 	= $cnt;
        for ($i = strlen($cnt); $i < $totalLen; $i++){
            $result	= "0".$result;
        }
        return $result;
    }
}

?>