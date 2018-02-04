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

//include("./html/head.html");
//include("./html/body.html");

$temp = new sensor();
$html = new html();
//$html->htmlAnf();
$temp->sensAnz();
//$html->htmlEnd();

include("./html/footer.html");