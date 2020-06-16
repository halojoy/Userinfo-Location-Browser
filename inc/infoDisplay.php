<?php

if (!is_file('data/locbrowser.db')) {
    require 'data/createDB.php';
}
require 'src/Userinfo.php';
$info =  new Userinfo;
$info->displayInfo();
