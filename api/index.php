<?php
// var_dump ($_POST);
// die () ;
//ini_set('display_errors', '1');ini_set('display_startup_errors', '1');error_reporting(E_ALL);
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
// print ("__CUT_HERE__");
var_dump ($_POST);
$query = $_POST ["query"] ;
$table = $_POST ["table"];
unset ($_POST ["query"]) ;
unset ($_POST ["table"]) ;
$s = "uid" ;
$v = ":uid" ;

foreach($_POST as $name => $val) {
  $s .= ",".$name ;
  $v .= ",:".$name ;
}

$_POST ["uid"] = $uid ;

switch ($query) {
  case "insert":
    $statement = "INSERT into $table ($s) values ($v) ;" ;
    break ;
}

print ($sql) ;
$sql = $db -> prepare ($statement);
if ($sql->execute( $_POST )) {
  api_return (null, 200);
}
print ("__CUT_HERE__");
?>
