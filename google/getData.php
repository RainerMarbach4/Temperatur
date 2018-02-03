<?php

// This is just an example of reading server side data and sending it to the client.
// It reads a json formatted text file and outputs it.

$string = file_get_contents("sampleData.json");
//echo $string;

//include("../config/config.php");
include("../classes/class.temperatur.php");


$temp = new temperatur("../config/config.php");
// Instead you can query your database and parse into JSON etc etc
//print_r($temp->config);

$arr = array('Fruehbeet' => rand ( -30 , 60 ), 'Mistbeet' => rand ( -30 , 55 ), 'Terasse' => rand ( -30 , 44 ));

//$temp->print_r($arr);


echo($temp->getAktJson());