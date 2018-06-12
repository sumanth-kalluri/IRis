<?php
session_start();

if (isset($_SESSION['u_id'])) {
  // Makes it easier to read

  header("location:home.php?loggedIn");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login V2</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">

<!--===============================================================================================-->
<script>
function validateForm() {
  var a = document.forms["signup"]["first_name"].value;
  var b = document.forms["signup"]["last_name"].value;
  var c = document.forms["signup"]["email"].value;
  var d = document.forms["signup"]["pass"].value;
  var e = document.forms["signup"]["pass1"].value;
  if (a == "" || b == "" || c=="" || d=="" || e=="") {
      alert("All the fields must be filled out");
      return false;
  }
  else {
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    if (reg.test(c)==false){
            alert('Invalid Email Address');
            return false;
        }
    else if (d!=e){
          alert('Password not same');
          return false;
        }
    else
    {
          return true;
        }
  }

}
</script>
</head>
<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form name="signup" onsubmit="return validateForm()"action="signup.inc.php" method="POST">
				<!--	<span class="login100-form-title p-b-26">
							Welcome
					</span>-->
				<span class="login100-form-title p-b-20">
						SIGNUP
					</span>
          <div class="wrap-input100 validate-input" data-validate = "Valid email is: abc@xyz.com">
						<input class="input100" type="text" name="first_name">
						<span class="focus-input100" data-placeholder="First name"></span>
					</div>

          <div class="wrap-input100 validate-input" data-validate = "Valid email is: abc@xyz.com">
            <input class="input100" type="text" name="last_name">
            <span class="focus-input100" data-placeholder="Last name"></span>
          </div>

					<div class="wrap-input100 validate-input" data-validate = "Valid email is: abc@xyz.com">
						<input class="input100" type="text" name="email">
						<span class="focus-input100" data-placeholder="Email"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100" type="password" name="pass">
						<span class="focus-input100" data-placeholder="Password"></span>
					</div>

          <div class="wrap-input100 validate-input" data-validate="Enter password">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100" type="password" name="pass1">
						<span class="focus-input100" data-placeholder="Confirm Password"></span>
					</div>

					<div>
            <span class="input100">
                School Or Teacher?
              </span>
							<div class="container">
                <label>
                  <span>&emsp;&ensp;</span>
  							<input type="radio" checked="checked" name="school" value="1">School
  							<span class="checkmark"></span></label>
                <span>&emsp;&ensp;</span>
						    <label>
  							<input type="radio" name="school" value="2">Teacher
  							<span class="checkmark"></span></label>
							</div>
          </div>


					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>

							<button type="submit" class="login100-form-btn" name="signup">
								Signup
							</button>

						</div>
					</div>
          <div class="text-center p-t-20">
            <span class="txt1">
              Already a user?
            </span>
            <a class="txt2" href="index.php">
              Sign In
            </a>
          </div>

					</div>
				</form>
			</div>
		</div>




<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>
