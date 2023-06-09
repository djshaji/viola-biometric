<?php
$dirname = $_POST ["autoid"] ;
$dirname = str_replace ("..", "", $dirname) ;
rmdir ("/var/www/viola/classes/$uid/$dirname") ;
?>