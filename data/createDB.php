<?php

$db = new PDO('sqlite:data/locbrowser.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

$sql = "CREATE TABLE IF NOT EXISTS userinfo(
    id INTEGER PRIMARY KEY,
    stamptime INTEGER,
    ipnumber  TEXT UNIQUE,
    city      TEXT,
    region    TEXT,
    country   TEXT,
    continent TEXT,
    timezone  TEXT,
    inetprov  TEXT,
    latitud   TEXT,
    longitud  TEXT,
    broname   TEXT,
    brovers   TEXT,
    browidth  TEXT,
    broheight TEXT,
    cookies   INTEGER,
    scrwidth  TEXT,
    scrheight TEXT,
    osystem   TEXT,
    os_64bit  INTEGER,
    device    TEXT
)";
$db->exec($sql);
$db = null;
