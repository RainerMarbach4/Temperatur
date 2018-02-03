<?php

/**
 * Created by PhpStorm.
 * User: rainer
 * Date: 24.03.2017
 * Time: 17:00
 */
class temperatur
{
    public $mysqli;

    function __construct($config = "./config/config.php") {
        error_reporting(E_ALL);
        include_once("$config");
        $this->config = $config;
        $this->queries = $queries;
        $this->connectDB();
    }

    function connectDB() {
        $this->mysqli = new mysqli($this->config["servername"], $this->config["username"], $this->config["password"], $this->config["dbname"]);
        if ($this->mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        $this->mysqli->set_charset('utf8');
    }

    public function aktuelleTemperaturen() {
        $html ="";
        foreach ($this->queries as $key => $value) {
            $sql = $value;
            //echo("$sql <br>");
            $sql = "SELECT * FROM `$sql`";
            //$this->print_r($sql);
            $res = $this->mysqli->query($sql);
            $resArr = array();
            //$this->print_r($res);
            while ($row = $res->fetch_assoc()) {
                $resArr[] = $row;
            }
            //$this->print_r($resArr);
            //echo($this->build_table($resArr, $value));
            $html.=$this->build_table($resArr, $value);
        }
        return $html;
    }

    public function getTableData($query) {
        $sql = "SELECT * FROM `$query`";
        //$this->print_r($sql);
        $res = $this->mysqli->query($sql);
        $resArr = array();
        while ($row = $res->fetch_assoc()) {
            $resArr[] = $row;
        }
        return $resArr;
    }

    public function setDateMinute($id, $datTime){
        $dt = strtotime($datTime);
        $dt2= date('Y-m-d H:i:00', $dt) ;
        $sql = "UPDATE `DataTable` SET `logdata` = '$dt2' WHERE `DataTable`.`id` = $id;";
        $res = $this->mysqli->query($sql);
        return $sql;
    }

    public function getAktJson($query = "AktuelleWerte") {
        $sql = "SELECT * FROM $query";
        //$this->print_r($sql);
        $res = $this->mysqli->query($sql);
        $resArr = array();
        $i = 1;
        //$res[0]['label']
        while ($row = $res->fetch_assoc()) {
            $resArr[$row['sensorname']] = $row['value'] * 1.0;
            //$resArr[$i]['value'] = $row['value'];
            $i++;
        }
        //$this->print_r($resArr);
        return json_encode($resArr);
    }


    public
    function print_r($val) {
        echo '<pre>';
        print_r($val);
        echo '</pre>';
    }

    function build_table($array, $title = "xxx") {
        if(count($array)==0){
            $html="";
            return $html;
        }
        $html = '<div id="' . $title . '" class="temp"><p>' . $title . '</p><table>';
        $html .= '<tr>';
        foreach ($array[0] as $key => $value) {
            $html .= '<th>' . $key . '</th>';
        }
        $html .= '</tr></div>';
        foreach ($array as $key => $value) {
            $html .= '<tr>';
            foreach ($value as $key2 => $value2) {
                if ($key2 == "link") {
                    if (empty($value2)) {
                        $html .= '<td>/</td>';
                    } else {
                        $html .= '<td>' . "<a href=\"$value2\" target=\"_blank\">detail</a>" . '</td>';
                    }
                } else {
                    $html .= '<td>' . $value2 . '</td>';
                }
            }
            $html .= '</tr>';
        }
        $html .= '</table></div><br>';
        return $html;
    }
}


