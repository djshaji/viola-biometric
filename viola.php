<?php
function pic ($url) {
  return str_replace ("http://", "/photos/", $url) ;
}

function console () {
  ?>
<!-- Modal -->
<div class="modal fade" id="console" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Console</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="console-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  <?php
}

function spinner () {
  ?>
  <div class="d-none spinner-border text-primary spinner" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>

  <?php
}

function checkmark () {
  ?>
    <i class="d-none text-success h3 text-bold fas fa-check-circle check"></i>
  <?php
}

function failed () {
  ?>
    <i class="fas text-danger d-none fa-exclamation-circle h3 text-bold failed"></i>
  <?php
}

function api_return ($data, $code) {
  // include "anneli/footer.php";
  if ($data == null)
    $data = array (); 

  $data ["response"] = $code ;
  $data = json_encode ($data) ;
  die ("__CUT_HERE__" . $data) ;
}

?>

<script>
ui = document.getElementById
uic = document.getElementsByClassName

function do_post (url, element, callback = null) {
  document.getElementsByClassName ("spinner")[0].classList.remove ("d-none")
  document.getElementsByClassName ("check")[0].classList.add ("d-none")
  document.getElementsByClassName ("failed")[0].classList.add ("d-none")

  data = {}
  console.log (`using element ${element}`)
  for (tag of ["select", "input"]) {
    d = document.getElementById (element).getElementsByTagName (tag)
  //  console.log (d)
    for (e of d) {
//      console.log (e, e.value)
      if (e.type == "checkbox")
        data [e.id] = e.checked
      else
        data [e.id] = e.value
    }
  }

  console.log (`posting to ${url}`)
  console.log (data)
  $.post (url, data, function (result, status) {
    console.log (result)
    document.getElementById ("console-body").innerHTML = result
//    $("console").modal ("show")
    r = result.split ("__CUT_HERE__")[2]
    console.log (`${status}: ${r}`)
    document.getElementsByClassName ("spinner")[0].classList.add ("d-none")

    if (typeof (r)== "undefined") r = '{"response":500}'
    if (JSON.parse (r) ["response"] == 200) {
      if (callback == null ) {
          Swal.fire(
            'Ok',
            'Data added successfully',
            'success'
          ).then(() => {
            document.getElementById ("close-dialog").click ()
          })      
      } else {
        callback (r) ;
      }
      
      document.getElementsByClassName ("check")[0].classList.remove ("d-none")
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Failed',
        text: 'Data could not be added',
        footer: r
      })

      document.getElementsByClassName ("failed")[0].classList.remove ("d-none")
    }
  })
}

function pic (url) {
  return url.replace ("http://", "/photos/")
}

</script>
