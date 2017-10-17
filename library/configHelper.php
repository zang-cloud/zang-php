<?php
/**
 * Created by PhpStorm.
 * User: Z003HDYW
 * Date: 23.4.2017.
 * Time: 23:56
 */

$configuration;
$includedFiles = debug_backtrace();
try {
    if (array_key_exists(1, $includedFiles) && strpos($includedFiles[1]["file"], "Test") !== false) {
        $configuration = parse_ini_file(dirname(dirname(__FILE__)) . "/tests/configuration/application.config.ini");
    } else {
        $configuration = parse_ini_file(dirname(dirname(__FILE__)) . "/configuration/application.config.ini");
    }
} catch (ZangException $e){
    trigger_error($e ->getMessage());
}

foreach ($configuration as $key => $value){
    $_ENV[$key] = $value;
}