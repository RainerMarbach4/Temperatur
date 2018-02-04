<?php

// This is just an example of reading server side data and sending it to the client.
// It reads a json formatted text file and outputs it.

include("../classes/class.temperatur.php");

$temp = new temperatur("../config/config.php");

echo($temp->getAktJson());