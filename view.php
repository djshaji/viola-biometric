<?php
// ini_set('display_errors', '1');ini_set('display_startup_errors', '1');error_reporting(E_ALL);
require ("vendor/autoload.php");

chdir (__DIR__);
include "config.php";
include "anneli/header.php" ;
include "anneli/db.php" ;
include "viola.php";
require_login ();
$classid = $_GET["id"] ;
$sql = "SELECT * from attendance where uid = :uid and cid = :cid ";
$res = $db -> prepare ($sql) ;
$data = array (
  "uid"=> $uid,
  "cid" => $classid
) ;

$res -> execute ($data) ;
$data = $res -> fetchAll () ;
// var_dump ($data [0]["course"]);
$students = [] ;
$dates = [] ;
$attendance = array () ;
$attended = array () ;
$total_days = sizeof ($data);
foreach ($data as $d) {
  $j = json_decode ($d ["students"], true);
  array_push ($dates, $d ["date"]);
  foreach ($j as $rollno => $val) {
    // array_push ($students, $rollno) ;
    if ($attendance [$rollno] == null)
      $attendance [$rollno] = array () ;
    if ($attended [$rollno] == null)
      $attended [$rollno] = 0 ;
    $attendance [$rollno][$d ["date"]] = $val ;
    if ($val == "P")
      $attended [$rollno] ++ ;
  }
}

// var_dump ($dates);
?>

<section class="container m-3 shadow">
  <div class="alert alert-primary h3"><?php echo $data [0]["course"] . " [". $total_days . " days]"; ?></div>
  <table class="table">
    <thead>
      <th>Roll No</th>
      <th>Attended</th>
      <th>%age</th>
      <?php foreach ($dates as $_d) {
        $d = explode (" +", $_d)[0] ;
        // var_dump (explode (", ", $d)[1]);
        $d = explode (", ", $d) [1] ;
        $d = str_replace (date ("Y", time ()), "<br><span class='mdl-chip__text'>", $d);
        $d .= "</span>";
        // $d = explode (date ("Y", time ()), $d) [0];
        // echo "<th class='mdl-button' style='position:relativea;left:-100;top:-100;transforma: rotate(-90deg);'>". $d ."</th>";
        echo "<td class='fs-6'>$d</td>";
      }
      ?>
    </thead>
    <tbody>
      <?php foreach ($attendance as $rollno => $att) {
        echo "<tr>" ;
        echo "<td>$rollno</td>";
        echo "<td>". $attended [$rollno] . "</td>" ;
        $attended_by_this = ($attended [$rollno] / $total_days )* 100 ;

        $color = "text-black" ;
        if ($attended_by_this < 75)
          $color = "text-danger text-bold";
        
        if ($attended_by_this > 90)
          $color = "text-success text-bold";
        
        echo "<td class='$color'>". $attended_by_this . "</td>" ;

        foreach ($dates as $d) {
          $v = $att [$d]  ;
          $color = "text-black" ;
          switch ($v) {
            case "P":
              $color = "text-success" ;
              break ;
            case "A":
              $color = "text-danger" ;
              break ;
            case "L":
              $color = "text-warning" ;
              break ;
          }
          echo "<td class='$color'>" . $v . "</td>" ;
        }

        echo "</tr>" ;
      }
      ?>
    </tbody>
  </table>
</section>

<?php 
include "anneli/footer.php" ;
?>