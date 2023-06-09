<?php
// var_dump ($_POST);
// die () ;
ini_set('display_errors', '1');ini_set('display_startup_errors', '1');error_reporting(E_ALL);
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
//var_dump ($_POST);
$query = $_POST ["query"] ;
$table = $_POST ["table"];
$script = $_POST ["script"] ;

unset ($_POST ["script"]) ;
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
$deleted_students = [] ;
echo "----| DEBUG [$query : $table]" ;
switch ($query) {
  case "insert":
    $statement = "INSERT into $table ($s) values ($v) ;" ;
    break ;
  case "remove-class":
    $statement = "DELETE from $table where autoid = :autoid and uid = :uid" ;
    $_POST = array (
      "uid"=> $uid,
      "autoid"=> $_POST ["autoid"]
    ) ;
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
  case "delete-class":
    # actually delete student from class
    $_autoid = $_POST ["autoid"] ;
    $_sql = "SELECT students from classes where autoid = :autoid" ;
    $_data = array ("autoid"=>$_autoid) ;
    $sql = $db -> prepare ($_sql);
    if (!$sql -> execute ($_data))
      die ('{"response":"501"}') ;
    $res = $sql -> fetch () ;
    if ($res == null) {
      var_dump ($_POST) ;
      die ("no students found") ;
    }

    $students = json_decode ($res ["students"], true) ;
    foreach ($_POST as $key=>$val) {
      if ($val == "false" || $val == "")
        unset ($_POST [$key]) ;
      else if ($val == "true") {
        unset ($_POST [$key]) ;
        unset ($students [$key]);
        array_push ($deleted_students, $key);
      }
    }

    $statement = "UPDATE classes set students = :students where uid = :uid and autoid = :autoid" ;
    $_POST ["students"] = json_encode ($students) ;

    break ;
  case "add-class":
    # actually add student to class
    $students = array () ;
    $_autoid = $_POST ["autoid"] ;
    $_sql = "SELECT students from classes where autoid = :autoid" ;
    $_data = array ("autoid"=>$_autoid) ;
    $sql = $db -> prepare ($_sql);
//    echo "----| fetching students [$_sql]" ;
    if (!$sql -> execute ($_data))
      die ('{"response":"501"}') ;
    $res = $sql -> fetch () ;
//    echo "----| DEBUG |------" ;
//    var_dump ($res) ;
    if ($res != null)
      $students = json_decode ($res ["students"], true) ;

//    var_dump ($students) ; 
    $statement = "UPDATE classes set students = :students where uid = :uid and autoid = :autoid" ;
    unset ($_POST ["subjects"]) ;

    foreach ($_POST as $key=>$val) {
      if ($val == "false" || $val == "")
        unset ($_POST [$key]) ;
      else if ($val == "true") {
        unset ($_POST [$key]) ;
        $students [$key] = true ;
      }
    }
    $_POST ["students"] = json_encode ($students) ;
    break ;
}

var_dump ($statement);
var_dump ($_POST);
var_dump ("script:" .$script);
$sql = $db -> prepare ($statement);
if ($sql->execute($_POST )) {
  if (file_exists ("/var/www/viola/scripts/$script.php")) {
    $script = str_replace ("..", "", $script) ;
    include "/var/www/viola/scripts/$script.php" ;
  }

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
