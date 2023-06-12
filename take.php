<?php
//ini_set('display_errors', '1');ini_set('display_startup_errors', '1');error_reporting(E_ALL);
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
$classid = $_GET["id"] ;
$sql = "SELECT * from classes where uid = '$uid' and autoid = '" . $classid . "'";
$res = $db -> prepare ($sql) ;
$res -> execute () ;
$course_info = $res -> fetch ();
// var_dump ($course_info);
$_sql = "SELECT * from students where rollno like :rollno" ;
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

?>
<h3 class="alert alert-primary">
  Semester <?php echo $course_info ["semester"] . " ". $course_info ["name"] . " " . $course_info ["course"] . " Section " . $course_info ["section"];?>
</h3>
<div class="section">
  <div class="row m-4 p-4 shadow justify-content-center">
    <div class="col-12 mb-3 justify-content-center  d-flex">
      <label for="" class="h3 align-self-center">
        <i class="fas fa-calendar-check me-2"></i>
        <?php $date = date ("r", time()) ; echo explode ("+", $date) [0];?>
      </label>
      <button class="ms-3 m-1 btn btn-primary" onclick="do_post ('/api/index.php', 'my-body')"><i class="fas fa-save me-2"></i>Save</button>
    </div>

    <div class="col-4">
      <div class="input-group">
        <input type="file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
        <button class="btn btn-primary" type="button" id="inputGroupFileAddon04"><i class="fas fa-camera me-2"></i>Upload</button>
      </div>

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
        echo "<td><img width='150' src='". pic ($row ["photo"])."' class='img-fluid' ></td>" ;
        foreach (["name", "rollno", "crollno"] as $tag)
          echo "<td>" . $row [$tag] . "</td>" ;
        $rollno = $row ['rollno'];
        print ("<td><select class='form-select' id='$rollno'>");
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
</script>