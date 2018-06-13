<?php
/* Displays user information and some useful messages */
session_start();

// Check if user is logged in using the session variable
if (isset($_SESSION['u_id'])) {
  // Makes it easier to read
  $first_n = $_SESSION['u_first'];
  $last_n = $_SESSION['u_last'];
  $Email = $_SESSION['u_email'];
  $isschool = $_SESSION['is_school'];
}
else {

    $_SESSION['message'] = "You must log in before viewing your profile page!";
		header("location: ../index.php?error=You must log in before viewing your profile page!");
}
?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>N E E V</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="../assets/css/main.css" />
	</head>
	<body>

		<!-- Header -->
			<header id="header">
				<div class="inner">
					<a href="../home.php" class="logo"><strong><font size="10">N E E V</font></strong> </a>
          <a href="../logout.php">	<button type="submit" name="login">
                Logout
            </button></a>
            <a href="profile.php">	<button type="submit" name="login">
                  Add a student
              </button></a>
				</div>
			</header>

		<!-- Banner -->

			<section id="banner">
				<div class="inner"><header>
          	<h1>Add a student </h1>
          </header>
									<form name="ADD" action="profile.inc.php" method="POST">
										<div class="row uniform">
											<div class="6u 12u$(xsmall)">
												<input type="text" name="first_name" placeholder="First Name" />
											</div>
                      <br><br><br>
											<div class="6u$ 12u$(xsmall)">
  												<input type="text" name="last_name" placeholder="Last Name" />
  											</div>
                        <br>

											<div class="6u$ 12u$(xmall)">
												<div class="select-wrapper">

                            <select name="class">
														<option value="0">- Class -</option>
														<option value="1">Class 1</option>
														<option value="2">Class 2</option>
														<option value="3">Class 3</option>
														<option value="4">Class 4</option>
														<option value="5">Class 5</option>
														<option value="6">Class 6</option>
														<option value="7">Class 7</option>
														<option value="8">Class 8</option>

													</select>
												</div>
											</div>
											<div class="12u$">
												<textarea name="remarks"  placeholder=" Remarks (if any) " rows="6"></textarea>
											</div>
                      <div class="6u 12u$(xsmall)">
												<input type="password" name="pass" placeholder="Enter Your Password to confirm" />
											</div>

											<div class="12u$">
													<span><button type="submit" name="ADD" /> ADD </button> </span>
												<!--	<span><input type="reset" value="Reset" class="alt" /></sapn>
-->
											</div>
                      	</div>
									</form>


					<footer>
					<br>
          <br>
          </br>
          </br>
					</footer>

			</section>


		<!-- Scripts <a href="#" class="button"></a> -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/skel.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>

	</body>
</html>
