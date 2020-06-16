<?php

if (!is_file('data/locbrowser.db')) {
    require 'data/createDB.php';
}
require 'src/Userinfo.php';
$info =  new Userinfo;
$ip = $info->getIP();
$ua = $info->getUserAgent();
if ($ip && $ua) {
    $info->storeInfo($ip, $ua);
}
