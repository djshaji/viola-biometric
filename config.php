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
            <label class="mdl-button mdl-js-button mdl-button--icon" for="search">
              <i class="material-icons">search</i>
            </label>
            <div class="mdl-textfield__expandable-holder">
              <input onchange="searchFiles();" class="mdl-textfield__input" type="text" id="search">
              <label class="mdl-textfield__label" for="sample-expandable">Search</label>
            </div>
          </div>'
  );
  
  $drawer_controls = array () ;
//  if ($uid != null)
  $config ["drawer"]["My Account"] = "/view.php?my=1" ;
  $config ["drawer"]["About"] = "/anneli/about.php" ;
  $root_user = 'lWDjT6ENhgV9Hs6JHIjFAcacpAo1' ;
?>
<!-- <script src="util.js"></script> -->
