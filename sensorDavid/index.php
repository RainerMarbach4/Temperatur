<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 11.10.2016
 * Time: 13:05
 */

include_once("functions.php");

$allImports = array();
$importKeys = array("css", "js");

$di = new RecursiveDirectoryIterator('libs');

$import_files = [];
foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
    if(endsWith($filename, ".php")) {
        array_push($import_files, $filename);
    }
}
sort($import_files);

foreach($import_files as $nu => $filename) {
    $import = null;
    include($filename);
    if(isset($import)) {

        foreach ($importKeys as $key) {
            if(!array_key_exists($key, $allImports)) {
                $allImports[$key] = array();
            }

            if(array_key_exists($key, $import)) {
                foreach ($import[$key] as $tmpKeyVal) {
                    if (startsWith($tmpKeyVal, "https://") || startsWith($tmpKeyVal, "http://")) {
                        array_push($allImports[$key], $tmpKeyVal);
                    } else {
                        array_push($allImports[$key], str_replace("\\", "/", dirname($filename) . "/" . $tmpKeyVal));
                    }
                }
            }
        }
    }
}

echo "<!DOCTYPE html>
<html lang=\"de\">
<head>
<meta charset=\"utf-8\"/>\n\r";


echo "<title>Sensoring</title>\n\r";

echo "<link href=\"icon.png\" rel=\"icon\">\n\r";
foreach ($allImports["css"] as $cssImport) {
    echo "<link rel='stylesheet' href='" . $cssImport . "'>\n\r";
}

echo "</head><body>";

include("html/main.html");


foreach ($allImports["js"] as $jsImport) {
    echo "<script src='" .  $jsImport . "'></script>\n\r";
}