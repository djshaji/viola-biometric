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
  if ($_GET ["q"] != null)
    if ($name != "")
        $_GET [$name] = $val ;
}

$_POST ["uid"] = $uid ;
$like = false ;
if ($_GET ["q"] != null) {
  $query = $_GET ["q"];
  $table = $_GET ["t"];
  unset ($_GET ["q"]) ;
  unset ($_GET ["t"]);
  $like = $_GET ["like"] ;
  unset ($_GET ["like"]) ;
}

$RETVAL = false ;
switch ($query) {
  case "insert":
    $statement = "INSERT into $table ($s) values ($v) ;" ;
    break ;
  case "get":
    $statement = "SELECT * from $table where 1 " ;
//    $sql -> bindparam ("autoid", $_GET ["id"], PDO::PARAM_INT) ;
    $_POST = $_GET ;
    $RETVAL = true ;
    foreach ($_POST as $key => $val) {
      if ($like)
        $statement .= " and $key like :$key" ;
      else
        $statement .= " and $key = :$key" ;
    }
    break ;
}

var_dump ($statement);
var_dump ($_POST);
$sql = $db -> prepare ($statement);

if ($sql->execute($_POST )) {
  if ($RETVAL) {
    $retval = $sql -> fetchAll () ;
    $retval ["response"] = 200 ;
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
