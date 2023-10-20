<?php
const INCLUDE_ROOT = __DIR__ . DIRECTORY_SEPARATOR;
function autoload($class)
{
    require_once INCLUDE_ROOT . "lib" . DIRECTORY_SEPARATOR . $class . ".php";
}

spl_autoload_register("autoload");
header("Access-Control-Allow-Origin: *");

