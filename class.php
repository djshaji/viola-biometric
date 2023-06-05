<?php
//ini_set('display_errors', '1');ini_set('display_startup_errors', '1');error_reporting(E_ALL);
require ("vendor/autoload.php");

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
//echo "$sql" ;
var_dump ($course_info);

?>
<h3 class="alert alert-primary">
  Semester <?php echo $course_info ["semester"] . " ". $course_info ["name"] . " " . $course_info ["course"] . " Section " . $course_info ["section"];?>
</h3>

<div class="section m-3 p-3 shadow">
  <table class="table">
    <thead>
      <th></th>
    </thead>
  </table>

  <div class="card-footer text-muted justify-content-center d-flex">
    <button class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#add">Add Students</button>

  </div>
  
</div>
<?php
//console () ;
include "anneli/footer.php" ;
?>

<!-- Modal -->
<div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Students</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row justify-content-center">
          <div class="col-3">
            <label>Course Code</label>
            <select name="course" id="subjects" class="form-select">
              <option value='%UENTS-403%'>UENTS-403</option>
            </select>
          </div>
          <div class="col-2">
            <button onclick="do_post ('/api/index.php?t=students&q=get&like=1', 'add', update_list)" class="btn btn-info">Filter</button>
          </div>
          <div class="col-2 p-2">
            <?php spinner (); checkmark (); failed ();?>
          </div>
          <div class="col-2">
            <input type="number" placeholder="From" class="form-control">
          </div>
          <div class="col-2">
            <input type="number" placeholder="To" class="form-control">
          </div>
        </div>        
        <table class="table">
          <thead>
            <th><input type="checkbox"></th>
            <th>Photo</th>
            <th>Name</th>
            <th>Roll No</th>
            <th>Class Roll No</th>
          </thead>
          <tbody id="add-body">
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close-dialog">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script>
function update_list (jdata) {
    t = document.getElementById ("add-body")
    t.innerHTML = ""
    j = JSON.parse (jdata) 
    for (key in j) {
        if (key =="response")
            continue
        console.log (j [key])
        tr = document.createElement ("tr")
        tr.innerHTML = `
            <td><input type="checkbox"></td>
            <td><img width="150" class="img-fluid" src="${j[key]['photo']}"></td>
            <td>${j[key]["name"]}</td>
            <td>${j[key]["rollno"]}</td>
            <td>${j[key]["crollno"]}</td>`          
       t.appendChild (tr)
    }
}
</script>

<?php
console () ;
?>
