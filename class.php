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

$_sql = "SELECT * from students where rollno like :rollno" ;
$_data = array ("rollno"=>$course_info ["semester"] . "%") ;
$sql = $db -> prepare ($_sql);
if (!$sql -> execute ($_data))
  $data = [] ;
$data = $sql -> fetchAll () ;
$students = json_decode ($course_info ["students"], true) ;

//echo "$sql" ;
//var_dump ($data);
$counter = 1 ;

?>
<h3 class="alert alert-primary">
  Semester <?php echo $course_info ["semester"] . " ". $course_info ["name"] . " " . $course_info ["course"] . " Section " . $course_info ["section"];?>
</h3>

<div class="section m-3 p-3 shadow">
  <table class="table">
    <thead>
      <th><input onchange="select_all (this, 'my-body')" type="checkbox" class="form-check-input"/></th>
      <th>S. No</th>
      <th>Photo</th>
      <th>Name</th>
      <th>University Roll No</th>
      <th>Class Roll No</th>
    </thead>
    <tbody id='my-body'>
      <input type="hidden" value="delete-class" id="query">
      <input type="hidden" value="classes" id="table">
      <input type="hidden" value="<?php echo $course_info ["autoid"];?>" id="autoid">

      <?php foreach ($data as $row) {
//        var_dump ($students [$row ["rollno"]]) ;
        if ($students [$row["rollno"]] == null)
          continue ;
          
        echo "<tr>" ;
        echo "<td><input class='form-check-input' type='checkbox' id='" . $row ["rollno"] . "'></input></td>" ;
        echo "<td>$counter</td>";
        echo "<td><img width='150' src='". pic ($row ["photo"])."' class='img-fluid' ></td>" ;
        foreach (["name", "rollno", "crollno"] as $tag)
          echo "<td>" . $row [$tag] . "</td>" ;
        echo "</tr>";
        $counter ++ ;
      }
      ?>
    </tbody>
  </table>

  <div class="card-footer text-muted justify-content-center d-flex">
    <button class="m-2 btn btn-primary"  data-bs-toggle="modal" data-bs-target="#add"><i class="fas fa-plus-circle me-2"></i>Add Students</button>
    <button class="m-2 btn btn-danger" onclick="do_post ('/api/index.php', 'my-body')" ><i class="fas fa-trash me-2"></i>Delete</button>
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
      <div class="modal-header container">
        <h1 class="modal-title col fs-5" id="exampleModalLabel">Add Students</h1>
        <div class="col align-self-end mt-2">
          <?php spinner (); checkmark (); failed ();?>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <input type="hidden" value="add-class" id="query">
      <input type="hidden" value="classes" id="table">
      <input type="hidden" value="<?php echo $course_info ["autoid"];?>" id="autoid">
      <div class="modal-body" id="add-1">
        <div class="row justify-content-center">
          <div class="col-3">
            <label>Course Code</label>
            <select name="course" id="subjects" class="form-select">
              <option value='%UENTS-403%'>UENTS-403</option>
            </select>
          </div>
          <div class="col-2">
            <button onclick="do_post ('/api/index.php?t=students&q=get&like=1', 'add-1', update_list)" class="btn btn-info"><i class="fas fa-filter me-2"></i>Filter</button>
          </div>
          <div class="col-2">
            <input onchange="do_filter (this, true)"  type="number" placeholder="From" class="form-control">
          </div>
          <div class="col-2">
            <input onchange="do_filter (this, false)" type="number" placeholder="To" class="form-control">
          </div>
          <div class="col-1">
            <button onclick="reset_filter ()" class="btn btn-primary"><i class="fas fa-sync"></i></button>
        </div>
        <table class="table">
          <thead>
            <th><input type="checkbox" class="form-check-input" onchange="select_all (this)"></th>
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
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="close-dialog"><i class="fas fa-times-circle me-2"></i>Close</button>
        <button onclick="do_post ('/api/index.php', 'add')" type="button" class="btn btn-primary"><i class="fas fa-plus-circle me-2"></i>Add Students</button>
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
        tr.id = j[key]['crollno']
        photo = pic (j[key]["photo"])
        tr.innerHTML = `
            <td><input id="${j[key]['rollno']}" type="checkbox" class="form-check-input"></td>
            <td><img width="150" class="img-fluid" src="${photo}"></td>
            <td>${j[key]["name"]}</td>
            <td>${j[key]["rollno"]}</td>
            <td>${j[key]["crollno"]}</td>`          
       t.appendChild (tr)
    }

}

function do_filter (element, isUp) {
    val = element.value 
    d = document.getElementById ("add")
    inputs = d.getElementsByTagName ("tr")
    for (i of inputs) {
        if (i.classList.contains ("d-none"))
            continue
        if ((isUp && parseInt (i.id) < val) || (!isUp && parseInt (i.id) > val))
            i.classList.add ("d-none")
        else 
            i.classList.remove ("d-none")
      
    }
}

function reset_filter () {
    d = document.getElementById ("add")
    inputs = d.getElementsByTagName ("tr")
    for (i of inputs)
        i.classList.remove ("d-none")
}

function select_all (el, container = "add-body") {
  d = document.getElementById (container)
  inputs = d.getElementsByTagName ("tr")
  for (i of inputs) {
    if (! i.classList.contains ("d-none"))
      i.children[0].children[0].checked = el.checked
  }


}

</script>

<?php
console () ;
?>
