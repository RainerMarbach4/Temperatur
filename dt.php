<?php

/**
 * Created by PhpStorm.
 * User: rainer
 * Date: 24.03.2017
 * Time: 17:00
 */


spl_autoload_register(function ($class) {
    include 'classes/class.' . $class . '.php';
});

include("./html/head2.html");
include("./html/body2.html");

$temp = new temperatur();
$html = new html();
$html->htmlAnf();

$now = new DateTime();

set_time_limit ( 5555);
$dt = $temp->getTableData("DataTable");

foreach ($dt as $val) {
    echo $val['id'] . '::'.$val['logdata'] . '<br>';
    $temp->setDateMinute($val['id'], $val['logdata']);
}

$temp->print_r($dt);

$x = strtotime($dt[0]['logdata']);
echo("N: $x  \t" . date('Y-m-d H:i:00', $x)) ;

//$temp->aktuelleTemperaturen();
$html->htmlEnd();

include("./html/footer2.html");