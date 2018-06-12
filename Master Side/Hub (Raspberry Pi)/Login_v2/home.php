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
		header("location: index.php?error=You must log in before viewing your profile page!");
}
?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>N E E V</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body>

		<!-- Header -->
			<header id="header">
				<div class="inner">
					<a href="#home" class="logo"><strong><font size="10">N E E V</font></strong> </a>

						<a href="logout.php">	<button type="submit" name="login">
									Logout
							</button></a>


				</div>
			</header>

		<!-- Banner -->
			<section id="banner">
				<div class="inner">
					<header>
						<h1>Welcome <?= $first_n ?> <?= $last_n ?> </h1>

					</header>

					<div class="flex ">

						<div>
							<span class="icon fa-folder-open"></span>
							<h3><a href="home/master_content.php">Master Content</a></h3>

						</div>

						<div>
							<span class="icon fa-book"></span>
							<h3><a href="home/testing_module.php">Testing Module</a></h3>

						</div>
                                          </div>
					<div class="flex ">
						<div>
							<span class="icon fa-list-alt"></span>
								<h3><a href="home/eva-report/eva-report.php">Evaluation Reports</a></h3>
						</div>
						<div>
							<span class="icon fa-hospital-o"></span>
							<h3><a href="home/neev_clinic.php">NEEV Clinic</a></h3>
						</div>
					</div>
	</div>

					<footer>
					<br>
          <br>
          </br>
          </br>
					</footer>

			</section>


		<!-- Scripts <a href="#" class="button"></a> -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>
      
	</body>
</html>
