<?php

spl_autoload_register(function ($class) {
    include '../classes/class.' . $class . '.php';
});

$sensor = new sensor("../config/config.php");
$sensor->setQuery($_SERVER['QUERY_STRING']);
//echo ($sensor->getQuery());
echo ($sensor->setSensorValue());

