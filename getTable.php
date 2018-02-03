<?php
/**
 * Created by PhpStorm.
 * User: rainer
 * Date: 29.03.2017
 * Time: 15:09
 */

include("./classes/class.temperatur.php");

if (empty($_GET["q"])) {
    $query = "MinMax-aktuellerTag";
} else {
    $query = $_GET["q"];
}
if (empty($_GET["u"])) {
    $ueber = "MinMax-aktuellerTag";
} else {
    $ueber = $_GET["u"];
}

//echo("U:$ueber  :: Q:$query");
$tab = new temperatur();
//echo $tab->build_table($tab->getTableData($query), $ueber);

//echo("<div id='temperatur' style='border: 2px solid green'>" . $tab->aktuelleTemperaturen() . "</div>");

echo($tab->aktuelleTemperaturen());