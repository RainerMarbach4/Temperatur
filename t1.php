<?php
/**
 * Created by PhpStorm.
 * User: rainer
 * Date: 04.02.2018
 * Time: 11:28
 */
function roundToQuarterHour($timestring) {
    $minutes = date('i', strtotime($timestring));
    return $minutes - ($minutes % 5);
}

$now = new DateTime();
$rounded_seconds = round($now->getTimestamp()    / (10 * 60)) * (10 * 60);
$now->setTimestamp($rounded_seconds);
$datenow = $now->format("Y-m-d H:i:00");
echo("<hr>!!!!!!!!!! DN: $datenow <hr>");



/*
echo $_SERVER['QUERY_STRING'];

echo parse_str($_SERVER['QUERY_STRING'], $output);

print_r($output);
*/