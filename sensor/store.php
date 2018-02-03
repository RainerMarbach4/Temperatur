<?php


$servername = "localhost";
$username = "d025a46b"; // username for your database
$password = "";
$dbname = "d025a46b"; // Name of database

$now = new DateTime();
$CRLF = "\n\r";

$fieldToGet = $_GET['field'];

$conn = mysql_connect($servername, $username, $password);

if (!$conn)
{
    die('Could not connect: ' . mysql_error());
}
$con_result = mysql_select_db($dbname, $conn);
if(!$con_result)
{
    die('Could not connect to specific database: ' . mysql_error());
}


    $datenow = $now->format("Y-m-d H:i:33");
    $hvalue = $value;

    $sql = "INSERT INTO `DataTable`(`logdata`, `field`, `value`) VALUES (\"$datenow\",\"$field\",$value)";
    $result = mysql_query($sql);
    if (!$result) {
	die('Invalid query: ' . mysql_error());
    }
    echo "<h1>THE DATA HAS BEEN SENT!!</h1>";
    mysql_close($conn);