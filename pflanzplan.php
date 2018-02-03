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

$temp = new temperatur("./config/config.php");
$html = new html();
$html->htmlAnf();
$temp->aktuelleTemperaturen();
$html->htmlEnd();

