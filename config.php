<?php
$config = array (
    "dir"=> "/var/www/viola",
    "filesdir"=> "/var/www/viola/files",
    "serviceAccount"=> "/var/www/viola-admin/viola-biometric-firebase-adminsdk-hxxry-3c05e78353.json",
    "database" => "mysql:host=localhost;dbname=viola;charset=utf8mb4",
    "database_user" => "viola",
    "database_pass" => "jennahaze",
    "codename" => "saक्षam",
    "description" => "Face Recognition based attendance system",
    "skin" => "materia",
    "theme" => "blue_deep_orange",
    "font"=> "Montserrat",
    "header" => true,
    "header-bg" => "primary",
    "footer" => true,
    "footer-bg" => "text-white bg-primary ",
    "privacy-policy"=> null,
    "logo" => "/logo.png",

    "drawer" => array (),
    "messages" => array (
      "new"=> true
    ),
    "analytics" => false,
    "footer-floating" => '<!-- Expandable Textfield -->
          <div class="mdl-textfield pt-3 mdl-js-textfield mdl-textfield--expandable">
            <button class="btn btn-sm btn-info ms-1"  data-bs-toggle="modal" data-bs-target="#console">Console</button>
          </div>'
  );
  
  $drawer_controls = array () ;
//  if ($uid != null)
  $config ["drawer"]["My Account"] = "/view.php?my=1" ;
  $config ["drawer"]["About"] = "/anneli/about.php" ;
  $root_user = 'n1WsT98D8BWsmyMoLdJ6lSn7K9m1' ;

  function debug () {
    ini_set('display_errors', '1');ini_set('display_startup_errors', '1');error_reporting(E_ALL);    
  }
?>
<!-- <script src="util.js"></script> -->
