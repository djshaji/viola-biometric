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
$res = $res -> fetch ();
//echo "$sql" ;
var_dump ($res);

?>
<h3 class="alert alert-primary">
  <?php echo $res ["name"];?>
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
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Students</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
