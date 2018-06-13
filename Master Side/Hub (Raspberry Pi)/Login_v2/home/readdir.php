<?php
session_start();
if (isset($_SESSION['u_id'])) {
  $first_n = $_SESSION['u_first'];
  $last_n = $_SESSION['u_last'];
  $Email = $_SESSION['u_email'];
  $isschool = $_SESSION['is_school'];

  $dir = $_POST['p'];


// Open a directory, and read its contents
if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file = readdir($dh)) !== false){
      if($file!="." || $file!="..")
      echo $file . " ";
      }
      closedir($dh);
    }
  }
}
else {
    $_SESSION['message'] = "You must log in before viewing your profile page!";
		header("location: /login_v2/index.php?error=You must log in before viewing your profile page!");
}
