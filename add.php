<?php
//ini_set('display_errors', '1');ini_set('display_startup_errors', '1');error_reporting(E_ALL);
require ("vendor/autoload.php");

chdir (__DIR__);
include "config.php";
include "anneli/header.php" ;
include "anneli/db.php" ;
include "viola.php";

$classid = $_GET["id"] ;
require_login () ;

$sql = "SELECT * from classes where uid = :uid and autoid = :autoid";
$res = $db -> prepare ($sql) ;
$res -> execute (array (
  "uid"=>$uid,
  "autoid"=> $_GET ["id"]
)) ;

$course_info = $res -> fetch ();
$class_id = $_GET ["id"];
// var_dump ($course_info);
if ($_FILES ["image"] != null) {
  $dir = "classes/$uid/$class_id/photos/";
  $_img = $_FILES ["image"]["tmp_name"];
  $img = "classes/$uid/$class_id/photos/" . time () . ".jpg";
  if (! file_exists (dirname ($img)))
    mkdir (dirname ($img), 0777) ;
  // error_clear_last () ;
  if (!move_uploaded_file ($_img, $img)) {
    $error = error_get_last () ;
    print ("<div class='alert alert-danger'>Cannot move uploaded file:");
    var_dump (error_get_last ());
    echo "</div>";
    die () ;
  } else {
    // print ("<div class='alert alert-info'>$img</div>");
  }
}

?>

<h3 class="alert alert-primary">
  Semester <?php echo $course_info ["semester"] . " ". $course_info ["name"] . " " . $course_info ["course"] . " Section " . $course_info ["section"];?>
</h3>

<div class="section">
  <?php if ($img != null) { ?>
    <?php print ("<script>image = '$img';</script>") ; ?>
    <div class="row justify-content-center">
      <img id="img" src="<?php echo $img ;?>" alt="" class="col-10 img-fluid">
      <div class="m-2 col-6 justify-content-center d-flex">
        <div class="d-none" id="img-data">
          <input type="hidden" id="image" value="<?php echo $img ;?>">
          <input type="hidden" id="semester" value="<?= $course_info ["semester"] ;?>">
          <input type="hidden" id="folder" value="<?php echo "classes/$uid/$class_id/faces" ;?>">
        </div>
        <button onclick="detect_faces (false)" class="btn m-2 btn-primary"><i class="fas fa-search me-2"></i>Detect</button>
        <button onclick="detect_faces (true)" class="btn m-2 btn-info"><i class="fas fa-fingerprint me-2"></i>Recognize</button>
        <div class="m-2 p-2">
          <?php spinner () ;checkmark () ; failed ();?>   

        </div>
      </div>
    </div>
  <?php } else { ?>
    <div class="row m-4 p-4 shadow justify-content-center">
      <div class="col-4">
        <label for="" class="text-primary mb-2 fw-bold">Take photo to add students</label>
        <form class="input-group" method="post" enctype="multipart/form-data" action="?id=<?php echo $_GET["id"] ;?>">
          <input name="image" type="file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
          <button type="submit" class="btn btn-primary" type="button" id="inputGroupFileAddon04"><i class="fas fa-camera me-2"></i>Upload</button>
        </form>
      </div>

    </div>
<?php } ?>
</div>

<div class="section m-3 p-3 shadow">
  <table class="table">
    <thead>
      <th></th>
    </thead>
  </table>
</div>


<?php 
include "anneli/footer.php";
?>

<script>
function detect_cb (data) {
  img = document.getElementById ("img")
  img.src = img.src + "-detect"
  console.log(data)
}

function detect_faces (recognize) {
  if (! recognize)
    do_post ("/api/detect.py", "img-data", detect_cb)
  else
    do_post ("/api/recognize.py", "img-data", detect_cb)
}

function cloneCanvas(oldCanvas) {
  oldCanvas = document.getElementById (oldCanvas)
  //create a new canvas
  var newCanvas = document.createElement('canvas');
  var context = newCanvas.getContext('2d');

  //set dimensions
  newCanvas.width = oldCanvas.width;
  newCanvas.height = oldCanvas.height;

  //apply the old canvas to the new one
  context.drawImage(oldCanvas, 0, 0);

  //return the new canvas
  return newCanvas;
}


</script>