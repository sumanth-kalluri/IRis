<?php
if(isset($_POST['signup'])){
    include_once 'database/db_connect.php';

    $first=mysqli_real_escape_string($con,$_POST['first_name']);
    $last=mysqli_real_escape_string($con,$_POST['last_name']);
    $email=mysqli_real_escape_string($con,$_POST['email']);
    $pwd=mysqli_real_escape_string($con,$_POST['pass']);
    $school=mysqli_real_escape_string($con,$_POST['school']);
    $pwd1=mysqli_real_escape_string($con,$_POST['pass1']);


    //error handlers
    //check for emppty fields
    if(empty($first) ||empty($last) || empty($email) || empty($pwd) || empty($school)){
      header("Location:signup.php?signup=empty");
      exit();
    }
    elseif($pwd!=$pwd1){
      header("Location:signup.php?signup=password_not_same");
      exit();
    }
     else{
       if(!preg_match("/^[a-zA-Z]*$/",$first) || !preg_match("/^[a-zA-Z]*$/",$last) ){
         header("Location:signup.php?signup=invalid");
         exit();
       }
       else{
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
              header("Location:signup.php?signup=invalid_email");
              exit();
            }
            else{
              $sql="SELECT * FROM users WHERE email='$email'";
              $result = mysqli_query($con,$sql);
              $resultCheck = mysqli_num_rows($result);

              if($resultCheck > 0){
                header("Location:signup.php?signup=email_exist");
                exit();
              }else{
                //hashing the password
                $hashPwd = password_hash($pwd, PASSWORD_DEFAULT);
                //insert the user into database
                $sql1 = "INSERT INTO users (first_name, last_name, email, pass, school) VALUES ('$first','$last','$email','$hashPwd','$school');";
                mysqli_query($con,$sql1);
                header("Location:index.php?signup=success");
                exit();
              }
            }

       }

    }
}
else {
  header("Location:signup.php");
  exit();
}


 ?>
