<?php
$rollno = null ;
foreach ($students as $student => $val) {
  $rollno = $student [0] ;
  break ;
}

$_sql = "SELECT * from students where rollno like :rollno" ;
$_data = array ("rollno"=>$rollno . "%") ;

$sql = $db -> prepare ($_sql);
if (!$sql -> execute ($_data))
  $data = [] ;
$data = $sql -> fetchAll () ;
#http://college.jucc.in/Uploads/15/Photo/pic211156.PNG
foreach ($data as $row) {
  if ($students [$row["rollno"]] != null) {
    $photo = explode ("/Photo/", $row ["photo"])[1] ;
    $folder = "/var/www/viola/faces/$rollno/$photo" ;
    $autoid = $_POST["autoid"] ;
    if (!symlink ($folder, "/var/www/viola/classes/$uid/$autoid")) {
      var_dump (error_get_last ());
      die ("{'response': '502','message': 'cannot make symlink'}") ;
    }
  }
}
?>