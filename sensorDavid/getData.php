<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 17.10.2016
 * Time: 20:58
 */

$from = null;
$to = null;
$sensor = null;
$back = null;

if (isset($_GET["from"])) {
    $from = new DateTime($_GET["from"]);
    $from = $from->format('Y-m-d H:i:s');
}
if (isset($_GET["to"])) {
    $to = new DateTime($_GET["to"]);
    $to = $to->format('Y-m-d H:i:s');
}

if (isset($_GET["sensor"])) {
    $sensor = $_GET["sensor"];
}

if (isset($_GET["back"])) {
    $back = $_GET["back"];
}

include("config/db.php");

if (!isset($dbconf)) {
    exit("Database config not found");
}

$mysqli = new mysqli($dbconf["host"], $dbconf["username"], $dbconf["passwd"], $dbconf["dbname"]);
if ($mysqli->connect_errno) {
    //$error = Array("error" => true, "message" => "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    $error = Array("error" => true, "message" => "Failed to connect to MySQL");
    echo json_encode($error);
}

function getSensors($mysqli) {
    if (!$mysqli instanceof mysqli) return array();
    $query = "SELECT `ID`, `name` FROM `sensor_list`";
    $res = $mysqli->query($query);
    $sensors = array();
    if ($res->num_rows >= 1) {
        while ($row = $res->fetch_assoc()) {
            $name = $row["name"];
            $id = intval($row["ID"]);
            $sensors[$id] = $name;
        }
    }
    return $sensors;
}

function getMinTime($mysqli) {
    if (!$mysqli instanceof mysqli) return null;
    $res = $mysqli->query("SELECT MIN(date) FROM data");
    if ($res->num_rows == 1) {
        $row = $res->fetch_assoc();
        $ret = new DateTime($row["MIN(date)"]);
        $ret = $ret->format('Y-m-d H:i:s');
        return $ret;
    }
}

function getMaxTime($mysqli) {
    if (!$mysqli instanceof mysqli) return null;
    $res = $mysqli->query("SELECT MAX(date) FROM data");
    if ($res->num_rows == 1) {
        $row = $res->fetch_assoc();
        $ret = new DateTime($row["MAX(date)"]);
        $ret = $ret->format('Y-m-d H:i:s');
        return $ret;
    }
}

$sensors = getSensors($mysqli);

if ($sensor != null) {
    if (!in_array($sensor, $sensors)) {
        exit("Not a valid sensor: " . $sensor);
    } else {
        $sensors = array($sensor);
    }
}

if ($back != null) {
    try {
        $inter = new DateInterval($back);
        $to = new DateTime();
        $from = new DateTime();
        $from->sub($inter);

        //$to->modify("-1 hour");
        //$from->modify("-1 hour");

        $to = $to->format('Y-m-d H:i:s');
        $from = $from->format('Y-m-d H:i:s');
    } catch (Exception $e) {
        $error = Array("error" => true, "message" => $e->getMessage());
    }
} else {
    if ($from == null) {
        $from = getMinTime($mysqli);
    }

    if ($to == null) {
        $to = getMaxTime($mysqli);
    }
}

$retObj = array();
$retObj["minDate"] = $from;
$retObj["maxDate"] = $to;
$retObj["sensors"] = $sensors;
$retObj["data"] = array();

$data = array();

$dataQuery = "SELECT `data`, `date`, `sensor_id` FROM data WHERE date BETWEEN '$from' AND '$to'";
if (count($sensor) == 1) {
    $dataQuery = "SELECT `data`, `date`, `sensor_id` FROM data WHERE date BETWEEN '$from' AND '$to' AND `sensor_id` IN (SELECT `ID` FROM `sensor_list` WHERE `name` = '" . $sensors[0] . "')";
    //$dataQuery = "SELECT `data`, `date`, `sensor_id` FROM data WHERE `sensor_id` = '" . $sensors[0] . "' AND `date` BETWEEN '" . $from . "' AND '" . $to . "'";
    //dataQuery = "SELECT `data`, `data`, `sensor_id` FROM (SELECT `data`, `data`, `sensor_id` FROM data WHERE `sensor_id` = '" . $sensors[0] . "') WHERE `date` BETWEEN '$from' AND '$to'";
}
//echo $dataQuery;

$res = $mysqli->query($dataQuery);

$senToSen = Array();

while ($row = $res->fetch_assoc()) {
    $date = $row["date"];
    $senId = $row["sensor_id"];
    if (!array_key_exists($senId, $senToSen)) {
        $senToSen[$senId] = count($senToSen) + 1;
    }
    $senData = $row["data"];
    if (!array_key_exists($date, $data)) {
        $data[$date] = array();
    }
    $data[$date][$senToSen[$senId]] = $senData;
}

$retObj["data"] = $data;
$retObj["count"] = count($retObj["data"]);

echo json_encode($retObj);
