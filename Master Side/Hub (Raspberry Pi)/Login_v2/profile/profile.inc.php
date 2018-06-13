<?php
session_start();
if (isset($_SESSION['u_id'])) {
  // Makes it easier to read
  $first_n = $_SESSION['u_first'];
  $last_n = $_SESSION['u_last'];
  $Email = $_SESSION['u_email'];
  $isschool = $_SESSION['is_school'];

if(isset($_POST['ADD'])){
    include("../database/db_connect.php");

    $first=mysqli_real_escape_string($con,$_POST['first_name']);
    $last=mysqli_real_escape_string($con,$_POST['last_name']);
    $pwd=mysqli_real_escape_string($con,$_POST['pass']);
    $class=mysqli_real_escape_string($con,$_POST['class']);
    $remarks=mysqli_real_escape_string($con,$_POST['remarks']);
    $sql = "SELECT * FROM users WHERE email = '$Email'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $pwdCheck = password_verify($pwd,$row['pass']);
    //error handlers
    //check for emppty fields

    if(empty($first) || empty($last) || empty($class) || empty($pwd) ){
      header("Location:profile.php?error=field_empty");
      exit();
    }
    elseif(!$pwdCheck){
        header("Location:profile.php?error=wrong_pass");
        exit();
    }
    else{
                //insert into database

                $sql1 = "INSERT INTO student_map ( mentor_id, first_name, last_name,class, remarks) VALUES ('$Email','$first','$last','$class','$remarks');";
                mysqli_query($con,$sql1);
                $sql1 = "SELECT * FROM student_map WHERE first_name='$first' AND last_name = '$last' AND class=$class";
                $sql1 = mysqli_query($con,$sql1);
                $row = mysqli_fetch_assoc($sql1);
                $uid=$row['class']."_".$row['roll'];
                $sql1 = "UPDATE student_map SET u_id= '$uid' WHERE class='$class' AND roll= '$row[roll]'";
                $res=mysqli_query($con,$sql1);
                if(!$res){
                header("Location:profile.php?added=success");
              }else{
                header("Location:profile.php?added=success_$uid");
              }
                exit();
              }

}
else {
  header("Location:profile.php");
  exit();
  }
}
else {

    //$_SESSION['message'] = "You must log in before viewing your profile page!";
		header("location: ../index.php?error=1");
}
 ?>
