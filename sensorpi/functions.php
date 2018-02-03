<?php

function _exit() {
    echo "</body>";
    echo "</html>";
    exit();
}

//________________________________________________________________________________?______
// sqlite

// DB connect
function db_con($DBfile) {
    if (!$db = new PDO("sqlite:$DBfile")) {
        $e="font-size:23px; text-align:left; color:firebrick; font-weight:bold;";
        echo "<b style='".$e."'>Fehler beim �ffnen der Datenbank:</b><br/>";
        echo "<b style='".$e."'>".$db->errorInfo()."</b><br/>";
        die;
    }
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}

// DB Query
function db_query($sql) {
    global $db;
    $result = $db->query($sql) OR db_error($sql,$db->errorInfo());
    return $result;
}

//Function to handle database errors
function db_error($sql,$error) {
    die('<small><font color="#ff0000"><b>[DB ERROR]</b></font></small><br/><br/><font color="#800000"><b>'.$error.'</b><br/><br/>'.$sql.'</font>');
}
?>