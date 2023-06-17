<?php
$rollno = null ;
foreach ($students as $student => $val) {
  var_dump ($student);
  $rollno = intval ($student / 100000000 );
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
    $new_dir = "/var/www/viola/classes/$uid/$autoid/faces/$photo" ;
    if (! file_exists (dirname ($new_dir)))
      mkdir (dirname ($new_dir)) ;
    if (!symlink ($folder, $new_dir)) {
      var_dump (error_get_last ());
      // die ("{'response': '502','message': 'cannot make symlink'}") ;
    }
  }
}
?>