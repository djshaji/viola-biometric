<?php
//ini_set('display_errors', '1');ini_set('display_startup_errors', '1');error_reporting(E_ALL);
require ("vendor/autoload.php");

chdir (__DIR__);
include "config.php";
include "anneli/header.php" ;
include "anneli/db.php" ;
include "viola.php";
$sql = "SELECT * from classes where uid = '$uid'" ;
$res = $db -> prepare ($sql) ;
$res -> execute () ;
$res = $res -> fetchAll ();
//var_dump ($res);
$all_courses = file_get_contents ("courses.json");
print ("<script>all_courses = $all_courses</script>");
$all_courses = json_decode ($all_courses, true);

?>
<h3 class="alert alert-primary">
  Classes
</h3>

<div class="section m-3 p-3 shadow">
  <table class="table">
    <thead>
      <th colspan="10">Class</th>  
      <th></th>
    </thead>
    <tbody id="tbody">
    <?php
      foreach ($res as $row) {
        echo "<tr>" ;
        echo "<td><a class='nav-link' href='/class.php?id=" . $row ["autoid"]. "'>" . $row["name"] . "</td>" ;
        echo "<td class='justify-content-end d-flex'><a class='m-2 btn btn-primary' href='/class.php?id=" . $row ["autoid"] . "'><i class='fas fa-folder-open me-2'></i>View</a>" ;
        echo "<button class='btn btn-danger m-2' onclick='do_post (\"/api/index.php\", \"remove-".$row["autoid"]."\",reload);'><i class='fas fa-trash me-2'></i>Delete this class</button></td>" ;
        echo "<div id='remove-".$row["autoid"]. "'>" .
          "<input type='hidden' id='autoid' value='".$row["autoid"]."'>".
          "<input type='hidden' id='table' value='classes'>" .
          "<input type='hidden' id='query' value='remove-class'>" .
          "<input type='hidden' id='script' value='delete_class'></div>" ;
          
        echo "</tr>" ;
      }
      ?>
  
   </tbody>
  <table>  
  <div class="card-footer text-muted justify-content-center d-flex">
    <button class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#add_class"><i class="fas fa-plus-circle me-2"></i>Add Class</button>

  </div>
</div>

<?php
include "anneli/footer.php";
?>

<!-- Button trigger modal -->

<!-- Modal -->
<div id="add_class" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Class</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body row m-3 p-3">
        <div class="col-3">
          <label for="">Semester</label>
          <select onchange="sem_select (this)" id="semester" class="form-select">
            <option></option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>6</option>
          </select>
        </div>
        <div class="col-5">
          <label>Course Code</label>
          <select id="course" class="form-select col-md-5">
            <option></option>
          </select>
        </div>
        <div class="col-3">
          <label for="">Section</label>
          <select id="section" class="form-select">
            <?php
            foreach (range('A', 'Z') as $i) {
              print ("<option>$i</option>");
            }
            ?>
          </select>
        </div>
        <div class="col-12 mt-4">
          <label>Enter Class Name</label>
          <input id="name" required type="text" class="form-control" placeholder="Class Name" aria-label="Recipient's username">
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" id="table" value="classes">
        <input type="hidden" id="query" value="insert">
        <input type="hidden" id="script" value="create_class">
        <button id="close-dialog" type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button onclick="do_post ('/api/index.php', 'add_class', reload)" type="button" class="btn btn-primary"><i class="fas fa-plus-circle me-2"></i>Add Class</button>
        <?php spinner () ;checkmark () ; failed ();?>
      </div>
    </div>
  </div>
</div>

<?php
console () ;
?>

<script>
function sem_select (sem) {
  sel = document.getElementById ("course")
  sel.innerHTML = ""
  for (i in all_courses [sem.value]) {
    el = document.createElement ("option")
    el.innerText = i
    el.value = `%${i}%`
    sel.appendChild (el)
  }
}

</script>