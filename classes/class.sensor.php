<?php
/**
 * Created by PhpStorm.
 * User: rainer
 * Date: 04.02.2018
 * Time: 09:37
 */

class sensor extends temperatur
{
    private $query;

    public function setQuery($query) {
        parse_str($_SERVER['QUERY_STRING'], $this->query);
    }

    public function getSensorDay() {
        if (isset($this->query["lasttimestamp"])) {
            $tt = $this->query["lasttimestamp"];
        } else {
            $tt = "00:00";
        }
        $sql = "SELECT DATE_FORMAT(logdata,'%H:%i') zeit, value FROM `sensor-tag` 
                      WHERE DATE_FORMAT(logdata,'%H:%i') >= \"$tt\"
                      ORDER BY 1";
        $result = $this->mysqli->query($sql);
        $rows = $result->fetch_all (MYSQLI_ASSOC);
        return json_encode($rows);
    }

    public function sensAnz() {
        $html = "";

        $sql = "SELECT * FROM `sensoren`";
        $this->print_r($sql);
        $res = $this->mysqli->query($sql);
        $resArr = array();
        $this->print_r($res);
        while ($row = $res->fetch_assoc()) {
            $resArr[] = $row;
        }
        $this->print_r($resArr);
        //echo($this->build_table($resArr, "SENSOREN"));
        //$html.=$this->build_table($resArr, "SENSOREN");

        return $html;
    }
}