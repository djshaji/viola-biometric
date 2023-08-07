<?php
//ini_set('display_errors', '1');ini_set('display_startup_errors', '1');error_reporting(E_ALL);
require ("vendor/autoload.php");

chdir (dirname (__DIR__));
include "config.php";
include "anneli/header.php" ;
include "anneli/db.php" ;
include "viola.php";
// include "anneli/footer.php" ;

check_cli () ;
$a = "301620001" ;
print ($a [0]);