<?php

//TODO / Add to login logs / REMOVE ME

function getUserIpAddr()
{
    ini_set('error_reporting', E_ERROR);

    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        return $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        return $_SERVER['REMOTE_ADDR'];
    }
}

echo 'User Real IP - '.getUserIpAddr();

