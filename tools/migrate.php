<?php
//ini_set('display_errors', '1');ini_set('display_startup_errors', '1');error_reporting(E_ALL);
require ("../vendor/autoload.php");

chdir (dirname (__DIR__));
include "config.php";
include "anneli/header.php" ;
include "anneli/db.php" ;
include "viola.php";
// include "anneli/footer.php" ;

check_cli () ;
$subs = array () ;
$sql = "SELECT * from nep where semester = 3 and fee is not null" ;
$a = $db -> prepare ($sql) ;
$a -> execute () ;
$data = $a -> fetchAll () ;

foreach ($data as $row) {
  $crollno = $row ["rollno"] ;
  $rollno = "30610" . $crollno ;
  $sql = "SELECT autoid from students where rollno = '$rollno'" ;
  $d = $db -> prepare ($sql) ;
  $d -> execute () ;
  $r = $d -> fetch () ;
  if ($r !== false) {
    echo "------| WARNING: rollno $rollno already exists! |--------" ;
    continue ;
  }
  $subjects = "Major-" .$row ["major"] . ";Minor-" . $row ["minor"] ;
  $s1 = json_decode ($row ["subjects1"], true) ;
  $s3 = json_decode ($row ["subjects3"], true) ;
  $subjects .= ";Skill-" . $s1 ["skill"] . ";AEC-" . $s1 ["aec"] . ";MD-" . $s3 ["md"] ;
  $row ["subjects"] = $subjects ;

  $name = $row ["name"] ;
  $email = $row ["email"] ;
  $phone = $row ["phone"] ;
  $photo = $row ["oldphoto"];
  $sign = $row ["oldsign"];
  $regno = $row ["regno"];
  $sql = "INSERT into students (name,email,phone,photo,sign,regno,rollno,crollno, subjects) 
    values ('$name','$email','$phone','$photo','$sign','$regno','$rollno','$crollno', '$subjects')";

  print ($sql . "\n");
  $d = $db -> prepare ($sql);
  $d -> execute () ;
  foreach (explode (";", $subjects) as $ss) {
    if (!isset ($subs [$ss]))
      $subs [$ss] = 0 ;
    $subs [$ss] ++ ;
  }

  file_put_contents ("courses-2023.json", json_encode ($subs));
}