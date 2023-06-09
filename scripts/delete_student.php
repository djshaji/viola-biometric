<?php
$rollno = null ;
var_dump ($deleted_students);

foreach ($deleted_students as $student => $val) {
  $rollno = intval ($val / 100000000 );
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
  if (in_array ($row ["rollno"], $deleted_students)) {
    $photo = explode ("/Photo/", $row ["photo"])[1] ;
    $folder = "/var/www/viola/faces/$rollno/$photo" ;
    $autoid = $_POST["autoid"] ;
    if (!unlink ("/var/www/viola/classes/$uid/$autoid/$photo")) {
      var_dump (error_get_last ());
      // die ("{'response': '502','message': 'cannot make symlink'}") ;
    }
  }
}


?>