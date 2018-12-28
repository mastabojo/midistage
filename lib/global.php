<?php
// to be able to open fonts from network
header("Access-Control-Allow-Origin: *");

if(!defined('NL')) {
	define('NL', "\n");
}

error_reporting(E_ALL);

// debug
$debug = false;

/* 
 * Temp debugging functions
 * 
 */

// display error on screen
function D($var, $die = false, $comment = '') {
    // echo '<pre>*** DEBUG *******</pre>';
    echo $comment != '' ? "<pre>[$comment]</pre>" : '';
    echo '<pre>' . print_r($var, 1) . '</pre>';
    // echo '<pre>*** END DEBUG ***</pre>';

    if($die) {
        die();
    }
}

// write error in the error log
function DE($var, $comment = '') {
    $str  = '[' . date("d.m.Y H:i") . '] ';
    $str .= $comment != '' ? "$comment\n" : '';
    $str .= print_r($var, 1);
    error_log($str);
} 
