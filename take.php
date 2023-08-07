<?php
//phpinfo () ;die () ;

//ini_set('display_errors', '1');ini_set('display_startup_errors', '1');error_reporting(E_ALL);
//ini_set ("upload_tmp_dir", "/var/www/viola/files");
require ("vendor/autoload.php");
$att_values = [
  "Present",
  "Absent",
  "Duty",
  "Leave",
  "Other"
] ;

chdir (__DIR__);
include "config.php";
include "anneli/header.php" ;
include "anneli/db.php" ;
include "viola.php";
require_login ();
$classid = $_GET["id"] ;
$sql = "SELECT * from classes where uid = :uid and autoid = :autoid";
$res = $db -> prepare ($sql) ;
$res -> execute (array (
  "uid"=>$uid,
  "autoid"=> $_GET ["id"]
)) ;

$course_info = $res -> fetch ();
// var_dump ($course_info);
$_sql = "SELECT * from students where rollno like :rollno order by crollno" ;
$_data = array ("rollno"=>$course_info ["semester"] . "%") ;
$sql = $db -> prepare ($_sql);
if (!$sql -> execute ($_data))
  $data = [] ;
$data = $sql -> fetchAll () ;
$students = json_decode ($course_info ["students"], true) ;
//echo "$sql" ;
// var_dump ($students);
//var_dump ($data);
$counter = 1 ;
// var_dump ($_FILES);
$class_id = $_GET ["id"];
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
      </div>
    </div>
  <?php } ?>
  <div class="row m-4 p-4 shadow justify-content-center">
    <div class="col-12 mb-3 justify-content-center  d-flex">
      <label for="" class="h3 align-self-center">
        <i class="fas fa-calendar-check me-2"></i>
        <?php $date = date ("r", time()) ; echo explode ("+", $date) [0];?>
      </label>
      <button class="ms-3 m-1 btn btn-primary" onclick="do_post ('/api/index.php', 'my-body')"><i class="fas fa-save me-2"></i>Save</button>
    </div>

    <div class="col-4">
      <form class="input-group" method="post" enctype="multipart/form-data" action="/take.php?id=<?php echo $_GET["id"] ;?>">
        <input name="image" type="file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
        <button type="submit" class="btn btn-primary" type="button" id="inputGroupFileAddon04"><i class="fas fa-camera me-2"></i>Upload</button>
      </form>

    </div>

    <div class="form-group col-2">
      <!-- <label class="text-muted" >Mark all as</label> -->
      <select class="form-select" id="mark-all">
        <?php foreach ($att_values as $a) {
          $o = $a [0] ;
          echo "<option value='$o'>$a</option>";
        }
        ?>
      </select>
    </div>
    <div class="col-2">
      <button class="btn btn-success" onclick="mark_all ()">
        <i class="fas fa-edit me-2"></i>
        Mark All
      </button>

    </div>
  </div>
</div>
<div class="section m-3 p-3 shadow">
  <table class="table">
    <thead>
      <th>S. No</th>
      <th>Photo</th>
      <th></th>
      <th>Name</th>
      <th>University Roll No</th>
      <th>Class Roll No</th>
      <th>
      </th>
    </thead>
    <tbody id='my-body'>
      <input type="hidden" value="take" id="query">
      <input type="hidden" value="attendance" id="table">
      <input type="hidden" value="<?php echo $date;?>" id="date">
      <input type="hidden" value="<?php echo $course_info ["semester"] ;?>" id="semester">
      <input type="hidden" value="<?php echo $course_info ["section"] ;?>" id="section">
      <input type="hidden" value="<?php echo $course_info ["name"] ;?>" id="name">
      <input type="hidden" value="<?php echo $course_info ["course"] ;?>" id="course">
      <input type="hidden" value="<?php echo $course_info ["autoid"];?>" id="autoid">

      <?php foreach ($data as $row) {
//        var_dump ($students [$row ["rollno"]]) ;
        if ($students [$row["rollno"]] == null)
          continue ;
          
        echo "<tr>" ;
        echo "<td>$counter</td>";
        $rollno = $row ['rollno'];
        echo "<td><img width='150' src='". pic ($row ["photo"])."' class='img-fluid' ></td>" ;
        // echo "<td><div class='card'><canvas width='150' height='150' id='$rollno-c'></canvas><button onclick='addPhotoDialog (\"$data-bs-toggle='modal' data-rollno='$rollno' data-bs-target='#add-photo' class='btn btn-sm btn-primary'>Add Photo</button></div></td>";
        ?>
        <td>
          <div class="card d-none">
            <canvas width="150" height="150" id="<?=$rollno?>-c"></canvas>
            <button data-bs-toggle="modal" data-bs-target="#add-photo" onclick="savePhotoDialog ('<?=$rollno?>')" class="btn btn-primary btn-sm">
              Add photo
            </button>
          </div>
        </td>
        <?php
        foreach (["name", "rollno", "crollno"] as $tag)
          echo "<td>" . $row [$tag] . "</td>" ;
        print ("<td><select class='form-select' id='s-$rollno'><option></option>");
        foreach ($att_values as $a) {
          $option = $a [0];
          echo "<option value='$option'>$a</option>";
        }
        echo "</td></tr>";
        $counter ++ ;
      }
      ?>
    </tbody>
  </table>

  <div class="card-footer text-muted justify-content-center d-flex">
    <button class="m-2 btn btn-primary" onclick="do_post ('/api/index.php', 'my-body')"><i class="fas fa-save me-2"></i>Save</button>
    <div class="p-3">
      <?php spinner () ;checkmark () ; failed ();?>   
    </div>
  </div>
  
</div>
<?php
//console () ;
include "anneli/footer.php" ;
?>

<?php
console () ;
?>

<script>
function mark_all () {
  what = document.getElementById ("mark-all").value
  for (el of document.getElementsByTagName ("select")) {
    el.value = what
  }
}

_data = null
function detect_cb (data) {
  _data = data
  img = document.getElementById ("img")
  img_ = document.createElement ("img")
  img_.onload = function () {
    for (rollno in data) {
      // console.log (data [rollno])
      jdata = JSON.parse (data [rollno])
      const canvas = document.getElementById(rollno + '-c');
      if (canvas != null) {
        canvas.parentElement.classList.remove ("d-none")
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img_, jdata ["x"], jdata ["y"], jdata ["w"], jdata ["h"],0,0,150,150);
        // console.log (rollno + "-c")
        // document.getElementById (rollno + "-c").getContext ("2d").drawImage (document.getElementById ("img"), 966,485,68,68,0,0,300,300)
      } else {
        console.log (`console is null for ${rollno}`)
      }
    }
  }

  img.src = image + "-detect"
  img_.src = image 
  // img.setAttribute ("src", image + "-detect")
  // console.log (data)
  data = JSON.parse (data)
  for (rollno in data) {
    // console.log (data [rollno])
    // const canvas = document.getElementById(rollno + '-c');
    // if (canvas != null) {
    //   const ctx = canvas.getContext('2d');
    //   ctx.drawImage(img, data [rollno]["x"], data [rollno]["y"], data [rollno]["w"], data [rollno]["h"]);
    // }

    // print (rollno)
    d = document.getElementById ("s-" + rollno)
    // print (d)
    if (d != null) {
      d.value = "P"
      d.parentElement.parentElement.classList.add ("fw-bold")
      d.parentElement.parentElement.classList.add ("text-success")
    }
  }
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

function savePhotoDialog (rollno) {
  c = cloneCanvas (rollno + "-c")
  c.setAttribute ("type", "canvas")
  c.id = "photo"
  apc = document.getElementById ("apc")
  apc.innerHTML = ""
  apc.appendChild (c)
}

function savePhoto (rollno) {
  dataURL = document.getElementById (rollno + "-c").toDataURL ()
  $.ajax({
      type: "POST",
      url: "/api/add_photo.php",
      data: { 
         imgBase64: dataURL
      }
    }).done(function(o) {
      console.log('saved'); 

    });

}
</script>

<!-- Modal -->
<div class="modal fade" id="add-photo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add photo</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body justify-content-center" id="add-photo-body">
        <div id="apc"></div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="rollno" placeholder="name@example.com">
          <label for="rollno">University Roll No</label>
        </div>
        <input type="hidden" value="add-photo" id="query">
        <input type="hidden" value="<?php echo $course_info ["autoid"];?>" id="autoid">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button onclick="do_post ('/api/index.php', 'add-photo')" type="button" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save</button>
        <?php spinner () ;checkmark () ; failed ();?>   
      </div>
    </div>
  </div>
</div>
