<?php
//ini_set('display_errors', '1');ini_set('display_startup_errors', '1');error_reporting(E_ALL);
require ("vendor/autoload.php");

chdir (__DIR__);
include "config.php";
include "anneli/header.php" ;
include "anneli/db.php" ;
include "viola.php";

if ($root_user != $uid) { ?>
  <div class="alert alert-danger">
    Unauthorized 403
  </div>
  <?php die () ;?>
<?php } ?>

<?php
$data = "SELECT * from students" ;
$data = $db -> prepare ($data) ;

$data -> execute () ;
$data = $data -> fetchAll () ;

$courses = array () ;
$courses ["2"] = array () ;
$courses ["4"] = array () ;
$courses ["6"] = array () ;

foreach ($data as $row) {
  $subs = explode (";", $row ["subjects"]) ;
  foreach ($subs as $s) {
    if (! isset ($courses [$row ["rollno"][0]][$s]))
      $courses [$row ["rollno"][0]][$s] = 0 ;
    $courses [$row ["rollno"][0]][$s] ++ ;
  }
}

$json = json_encode ($courses, JSON_FORCE_OBJECT);
// file_put_contents ("courses.json", $json);
?>


<?php include "anneli/footer.php" ;

?>