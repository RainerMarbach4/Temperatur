<?php
$servername = "localhost";
$username = "d025a46b"; // username for your database
$password = "rl,.13";
$dbname = "d025a46b"; // Name of database
$now = new DateTime();
$CRLF = "\n\r";

$fieldToGet = $_GET['field'];
$val = $_GET['wert'];
$sensor = $_GET['sensor'];

$mysqli = new mysqli($servername, $username, $password, $dbname);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
$mysqli->set_charset('utf8');


$datenow = $now->format("Y-m-d H:i:00");
$hvalue = $value;

$sql = "INSERT INTO `DataTable` (`id`, `logdata`, `sensor`, `value`) VALUES (NULL, NOW(), '$sensor', $val);";
$sql = "INSERT INTO `DataTable` (`id`, `logdata`, `sensor`, `value`) VALUES (NULL, \"$datenow\", '$sensor', $val);";
//print_r($sql);
$mysqli->query($sql);


echo("#2#");
    