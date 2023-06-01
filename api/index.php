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
if ($_GET ["q"] != null) {
  $query = $_GET ["q"];
  $table = $_GET ["t"];
}


$RETVAL = false ;
switch ($query) {
  case "insert":
    $statement = "INSERT into $table ($s) values ($v) ;" ;
    break ;
  case "class":
    $statement = "SELECT * from $table where uid = '$uid' and autoid = :autoid" ;
//    $sql -> bindparam ("autoid", $_GET ["id"], PDO::PARAM_INT) ;
    unset ($_GET ["q"]) ;
    unset ($_GET ["t"]);
    $_POST = $_GET ;
    $RETVAL = true ;
    break ;
}

$sql = $db -> prepare ($statement);

if ($sql->execute($_POST )) {
  if ($RETVAL) {
    $retval = $sql -> fetch () ;
    $json = json_encode($retval, JSON_FORCE_OBJECT);
    print ("__CUT_HERE__");

    echo $json ;

  } else
    api_return (null, 200);
} else {
    echo "error " . error_get_last () ;
}
print ("__CUT_HERE__");
?>
