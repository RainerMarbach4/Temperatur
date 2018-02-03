<?php
    $dbhost="localhost";
    $dblogin="root";
    $dbpwd="";
    $dbname="kometschuh_blog";


$ip = "";
$pagename = "";
$secondleveldomain = "";

if( $_REQUEST["ip"] && $_REQUEST['pagename'] && $_REQUEST['secondleveldomain'])
{
	$ip = $_REQUEST['ip'];
	$pagename = $_REQUEST['pagename'];
	$secondleveldomain = $_REQUEST['secondleveldomain'];
}
$db =  mysql_connect($dbhost,$dblogin,$dbpwd);
mysql_select_db($dbname);

mysql_query("INSERT INTO analytics (
		ip,
		pagename,
		secondleveldomain,
		year,
		month,
		day,
		hour,
		minute,
        seconds)
        VALUES (
        '".$ip."',
        '".$pagename."',
        '".$secondleveldomain."',
        '".date('Y')."',
        '".date('m')."',
        '".date('d')."',
        '".date('H')."',
        '".date('i')."',
        '".date('s')."')");


mysql_close($db);
?>

