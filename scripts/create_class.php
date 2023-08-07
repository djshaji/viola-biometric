<?php
$_st = "SELECT autoid from classes where uid = :uid and name = :name and course = :course and semester = :semester and section = :section" ;
$_exec = $db -> prepare ($_st) ;
$_exec -> execute ($_POST) ;
$dirname = $_exec -> fetch () ["autoid"];
var_dump ($dirname);
echo "creating directory: /var/www/viola/classes/$uid/$dirname/faces";
if (!mkdir ("/var/www/viola/classes/$uid/$dirname", 0777, true))
  var_dump (error_get_last ());
else
  chmod ("/var/www/viola/classes/$uid/$dirname", 0777);
?>