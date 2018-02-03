<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 15.01.2017
 * Time: 17:15
 */

echo "<h1>";
if(isset($_GET["userid"])) {
    echo "User found: " . $_GET["userid"];
} else {
    echo "no user found";
}
echo "</h1>";
