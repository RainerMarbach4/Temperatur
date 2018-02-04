<?php
/**
 * Created by PhpStorm.
 * User: rainer
 * Date: 04.02.2018
 * Time: 09:37
 */

class sensor extends temperatur
{
    public function sensAnz() {
        $html ="";

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