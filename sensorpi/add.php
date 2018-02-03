<?php
//------------------------------------------------------------------------------
error_reporting(E_ALL);
ini_set('track_errors', 1);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
//------------------------------------------------------------------------------
require_once("config.php");
require_once("functions.php");
//------------------------------------------------------------------------------

// check if sqlite db file exists else create it..
if (!file_exists($DBfile)) {
    $db = db_con($DBfile);
    $SQL = "CREATE TABLE IF NOT EXISTS werte (id INTEGER PRIMARY KEY,time INT,nodeID INT,place TEXT,supplyV TEXT,temp TEXT,hum TEXT)";
    $create = db_query($SQL);
}

if (!empty($_GET)) {
    $ValidKey = false;
    foreach ($_GET AS $arg => $var) {
        if ($arg == "key" AND $var == $SECURITYKEY) { $ValidKey=true; }
        if ($arg == "node") { $nodeID = $var; }
        if ($arg == "v") { $supplyV = $var; }
        if ($arg == "t") { $temp = $var; }
        if ($arg == "h") { $hum = $var; }
    }
    if (!$ValidKey) { echo "Invalid Key!"; exit(); }

    if ( isset($nodeID) AND isset($supplyV) AND (isset($temp) OR isset($hum)) ) {
        if (!isset($hum)) {
            $SQL = "INSERT INTO werte (time,nodeID,place,supplyV,temp) VALUES ('".time()."','".$nodeID."','".$Sensor[$nodeID]."','".$supplyV."','".$temp."')";
        } else {
            $SQL = "INSERT INTO werte (time,nodeID,place,supplyV,temp,hum) VALUES ('".time()."','".$nodeID."','".$Sensor[$nodeID]."','".$supplyV."','".$temp."','".$hum."')";
        }
        $db = db_con($DBfile);
        $insert = db_query($SQL);
    }
}

?>