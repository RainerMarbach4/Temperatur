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

    /**
     * @param $queryStr
     */
    public function setQuery($queryStr) {
        parse_str($queryStr, $this->query);
    }

    /**
     * @return mixed
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * @param $query
     * @return array
     */
    function getQueryResultArray($query) {
        $retArr = array();

        if ($result = $this->mysqli->query($query)) {
            while ($row = $result->fetch_array(MYSQLI_NUM)) {
                $retArr[] = $row[0];
            }
            $result->free();
        }
        return $retArr;
    }

    /**
     * @return string
     */
    public function setSensorValue() {
        if (isset($this->query["wert"])) {
            if (($this->query["wert"] < -60) or ($this->query["wert"] > 60)) {
                return "value out of ..";
            }
        }
        if (isset($this->query["sensor"])) {
            $sensor = $this->query["sensor"];
        } else {
            return "no sensor";
        }
        if (isset($this->query["wert"])) {
            $val = $this->query["wert"];
        } else {
            return "no value";
        }

        $now = new DateTime();
        $rounded_seconds = round($now->getTimestamp() / (10 * 60)) * (10 * 60);
        $now->setTimestamp($rounded_seconds);
        $datenow = $now->format("Y-m-d H:i:00");

        $now2 = new DateTime();
        $datenow2 = $now2->format("Y-m-d H:i:s");

        $sql = "INSERT INTO `DataTable` (`id`, `logdata`, `sensor`, `value`, `anmerkung` ) VALUES (NULL, \"$datenow\", '$sensor', $val,  \"$datenow2\");";
        $this->mysqli->query($sql);

        return $this->mysqli->error;
    }

    public function getSensorDay() {
        if (isset($this->query["lasttimestamp"])) {
            $tt = $this->query["lasttimestamp"];
        } else {
            $tt = "00:00";
        }
        if (isset($this->query["sens"])) {
            // zeitachse
            $sql = "SELECT DATE_FORMAT(logdata, '%H:%i') AS TT
                        FROM T99
                        WHERE T99.sensornr=1 AND DATE_FORMAT(logdata,'%H:%i') >= \"$tt\"
                        ORDER BY T99.logdata";
            $rows["zeit"] = $this->getQueryResultArray($sql);


            // alle sensor werte der zeitachse entsprechend
            $sql = "SELECT sensoren.sensorname
                        FROM sensoren
                        ORDER BY sensoren.sensorname";
            $sensoren = $this->getQueryResultArray($sql);

            foreach ($sensoren as $sensorName) {
                $sql = "SELECT T99.value FROM T99
                        WHERE T99.sensorname='$sensorName' AND DATE_FORMAT(logdata,'%H:%i') >= \"$tt\"
                        ORDER BY T99.logdata";
                $rows[$sensorName] = $this->getQueryResultArray($sql);
            }
        } else {
            $sql = "SELECT DATE_FORMAT(logdata,'%H:%i') zeit, value FROM `sensor-tag` 
                      WHERE DATE_FORMAT(logdata,'%H:%i') >= \"$tt\"
                      ORDER BY 1";
            $result = $this->mysqli->query($sql);
            $rows = $result->fetch_all(MYSQLI_ASSOC);
        }
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