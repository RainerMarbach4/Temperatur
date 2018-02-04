<?php
/**
 * Created by PhpStorm.
 * User: rainer
 * Date: 29.03.2017
 * Time: 15:09
 */

include("../classes/class.temperatur.php");
$tab = new temperatur("../config/config.php");


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

echo($tab->aktuelleTemperaturen());