<?php

class Userinfo
{
    public function getIP()
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }elseif(!empty($_SERVER['REMOTE_ADDR'])){
            $ip = $_SERVER['REMOTE_ADDR'];
        }else{
            $ip = false;
        }
        return $ip;
    }

    public function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public function storeInfo($ip, $ua)
    {
        $db = new PDO('sqlite:data/locbrowser.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        $sql = "SELECT ipnumber FROM userinfo WHERE ipnumber = '$ip'";
        $ipnum = $db->query($sql)->fetchColumn();

        if (!$ipnum) {

            $url = 'http://ip-api.com/json/'.$ip.'?fields=3207167';
            $data = json_decode(file_get_contents($url));
            extract((array)$data);

            require 'src/BrowserDetection.php';
            $obj = new foroco\BrowserDetection;
            $all = $obj->getAll($ua);
            extract($all);

            $time = time();

            $sql = "INSERT INTO userinfo (
            stamptime, ipnumber, city, region, country, continent, timezone, inetprov,
            latitud, longitud, broname, brovers, osystem, os_64bit, device
            )VALUES(
            $time, '$query', '$city', '$regionName', '$country', '$continent', '$timezone',
            '$isp', '$lat', '$lon', '$browser_name', '$browser_version', 
            '$os_title', $os_64bit, '$device_type'
            )";
            $db->exec($sql);

        }
        $db = null;
    }

    public function displayInfo()
    {
        $db = new PDO('sqlite:data/locbrowser.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        $sql = "SELECT * FROM userinfo ORDER BY stamptime DESC LIMIT 10";
        $all = $db->query($sql)->fetchAll();

        foreach($all as $row) {
            echo '<table style="border-collapse:collapse">';
            echo '<tr><td>Time created</td><td>'.date('Y-m-d H:i', $row->stamptime).'</td></tr>';
            echo '<tr><td>IP</td><td>'.$row->ipnumber.'</td></tr>';
            echo '<tr><td>City</td><td>'.$row->city.'</td></tr>';
            echo '<tr><td>Region</td><td>'.$row->region.'</td></tr>';
            echo '<tr><td>Country</td><td>'.$row->country.'</td></tr>';
            echo '<tr><td>Continent</td><td>'.$row->continent.'</td></tr>';
            echo '<tr><td>Timezone</td><td>'.$row->timezone.'</td></tr>';
            date_default_timezone_set($row->timezone);
            $dst = date('I') ? ' DST' : '';
            $offset = 'UTC'.date('P').$dst;
            echo '<tr><td>Current offset</td><td>'.$offset.'</td></tr>';
            echo '<tr><td>Internet Provider</td><td>'.$row->inetprov.'</td></tr>';
            echo '<tr><td>Latitude</td><td>'.$row->latitud.'</td></tr>';
            echo '<tr><td>Longitude</td><td>'.$row->longitud.'</td></tr>';
            echo '<tr><td>Browser name</td><td>'.$row->broname.'</td></tr>';
            echo '<tr><td>Browser version</td><td>'.$row->brovers.'</td></tr>';
            echo '<tr><td>Operating System&nbsp;</td><td>'.$row->osystem.'</td></tr>';
            $os64 = $row->os_64bit ? 'Yes' : 'No';
            echo '<tr><td>OS 64 bits</td><td>'.$os64.'</td></tr>';
            echo '<tr><td>Device type</td><td>'.ucfirst($row->device).'</td></tr>';
            echo '</table>';
        }
    }


}