<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 11.10.2016
 * Time: 19:47
 */

/**
 * Checks if an String ends with the needle
 *
 * @param $haystack String to test
 * @param $needle String that should be on the end
 * @return bool If the string ends with the needle
 */
function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}

/**
 * Checks if an String starts with an needle
 *
 * @param $haystack String to test
 * @param $needle String that should be on the start
 * @return bool If the String starts with the needle
 */
function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}
