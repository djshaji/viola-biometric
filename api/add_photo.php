<?php
chdir ("/var/www/viola");
require ("vendor/autoload.php");
include "config.php";
include "anneli/header.php" ;
include "anneli/db.php";
//include "anneli/functions.php" ;
include "anneli/footer.php";
include "viola.php";
// require_login () ;
if ($uid == null) {
  api_return (null, 403);
}

