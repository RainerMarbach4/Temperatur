<?php
//setting header to json
header('Content-Type: application/json');

spl_autoload_register(function ($class) {
    include '../classes/class.' . $class . '.php';
});

$sensor = new sensor("../config/config.php");
$sensor->setQuery($_SERVER['QUERY_STRING']);
echo ($sensor->getSensorDay());


/*
 *
 {
	"zeit": ["t1", "t2", "t3"],
	"M4": [35, 12, 53, 12],
	"1. Terrasse": [12, 53, 12, 53]
}

CREATE VIEW `sensor-tag2`
    AS select `DataTable`.`logdata` AS `logdata`,`DataTable`.`value` AS `value`,
    `DataTable`.`sensor` AS `sensor`,year(`DataTable`.`logdata`) AS `jahr`,
    month(`DataTable`.`logdata`) AS `monat`,
    dayofmonth(`DataTable`.`logdata`) AS `tag` from `DataTable`

    where (
    (year(`DataTable`.`logdata`) = year(now())) and
    (month(`DataTable`.`logdata`) = month(now())) and
    (dayofmonth(`DataTable`.`logdata`) = dayofmonth(now())))

    order by `DataTable`.`logdata` desc;

 */