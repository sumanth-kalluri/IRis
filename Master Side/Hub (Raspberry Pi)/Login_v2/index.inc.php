<?php
session_start();

  if(isset($_POST['login'])) {
			include("database/db_connect.php");
    			$email = mysqli_real_escape_string($con,$_POST['email']);
					$pwd = mysqli_real_escape_string($con,$_POST['pass']);
					//error handlers
					//check if the inputs are empty
					if(empty($email)  || empty($pwd)){
						header("Location: index.php?login=empty");
						exit();
					}else{
						$sql = "SELECT * FROM users WHERE email = '$email'";
						$result = mysqli_query($con, $sql);
						$rescheck =  mysqli_num_rows($result);
						if($rescheck<1){
							header("Location: index.php?login=error");
							exit();
						}
					  else{
							if($row = mysqli_fetch_assoc($result)){
								//de hashing
                $hashPwdCheck=password_verify($pwd,$row['pass']);
								if(!$hashPwdCheck){
									header("Location:index.php?login=error");
								}

								elseif($hashPwdCheck = true){
									// log in the users
									$_SESSION['u_id']=$row['id'];
									$_SESSION['u_first']=$row['first_name'];
									$_SESSION['u_last']=$row['last_name'];
									$_SESSION['u_email']=$row['email'];
                  $_SESSION['is_school']=$row['school'];

									header("Location:home.php?login=success");
							}
						}
					}
				}
     }
					else{
						header("Location : home.php?login=error");
						exit(0);
					}
