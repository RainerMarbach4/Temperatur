<?php
/**
 * Created by PhpStorm.
 * User: rainer
 * Date: 04.02.2018
 * Time: 11:28
 */
function roundToQuarterHour($timestring) {
    $minutes = date('i', strtotime($timestring));
    return $minutes - ($minutes % 15);
}

$t = roundToQuarterHour(date("YY-m-d"));

echo $t;
