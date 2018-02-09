<?php

// This is just an example of reading server side data and sending it to the client.
// It reads a json formatted text file and outputs it.

include("../classes/class.temperatur.php");

$temp = new temperatur("../config/config.php");

/*
//Test code
$test = [];

for($i = 0; $i < 1; $i++) {
    $test["OmaEck" . $i] = rand(-300, 600) / 100;
}

echo(json_encode($test));
exit
/**/

echo($temp->getAktJson());
