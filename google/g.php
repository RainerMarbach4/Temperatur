<?php
/**
 * Created by PhpStorm.
 * User: rainer
 * Date: 28.03.2017
 * Time: 15:59
 */

$like = 20;
$table = array();
$table=array(0=>array('Label','Value'),1=>array('Likes',$like));


// encode the table as JSON
$jsonTable = json_encode($table);

// set up header; first two prevent IE from caching queries
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Oct 2013 05:00:00 GMT');
header('Content-type: application/json');


$arr = array('Fruehbeet' => rand ( -30 , 60 ), 'Mistbeet' => rand ( -30 , 55 ), 'Terasse' => rand ( -30 , 44 ));

echo json_encode($arr);

// return the JSON data
//echo $jsonTable;