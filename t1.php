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

$m = date("Y-m-d H:i:s");
$t = roundToQuarterHour($m);

echo $t;
echo("<hr>");
echo $m;
echo("<hr>");
echo date('i', strtotime($m));


$seconds = time();
$rounded_seconds = round($seconds / (10 * 60)) * (10 * 60);

echo "Original: " . date('H:i', $seconds) . "\n";
echo "Rounded: " . date('H:i', $rounded_seconds) . "\n";

echo $_SERVER['QUERY_STRING'];

echo parse_str($_SERVER['QUERY_STRING'], $output);

print_r($output);