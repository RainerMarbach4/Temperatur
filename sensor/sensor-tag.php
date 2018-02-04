<?php
//setting header to json
header('Content-Type: application/json');

include("../config/config.php");

$servername = $config["servername"];
$username = $config["username"];
$password = $config["password"];
$dbname = $config["dbname"];

$now = new DateTime();
$CRLF = "\n\r";


$fieldToGet = $_GET['field'];
$val = $_GET['wert'];
$sensor = $_GET['sensor'];
// To know what to send to the client
$lastTimeStamp = $_GET['lasttimestamp'];

$mysqli = new mysqli($servername, $username, $password, $dbname);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
$mysqli->set_charset('utf8');


$datenow = $now->format("Y-m-d H:i:00");
//$hvalue = $value;

$query = "SELECT DATE_FORMAT(logdata,'%H:%i') zeit, value FROM `sensor-tag` order by 1";
//print_r($sql);
$result = $mysqli->query($query);

$rows = $result->fetch_all (MYSQLI_ASSOC);  //mixed mysqli_result::fetch_all ([ int $resulttype = MYSQLI_NUM ] )
//$rows[] = array("zeit"=>"24:00");

print json_encode($rows);

    