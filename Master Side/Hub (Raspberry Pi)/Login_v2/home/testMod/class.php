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
$class = 1 ;
if(isset($_GET["cls"])){
  $class = $_GET["cls"] ;
}
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>N E E V</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="/Login_v2/assets/css/main.css" />
    <style>
      .click:hover{
        cursor:pointer;
      }
    </style>
  </head>
	<body>

		<!-- Header -->
			<header id="header">
				<div class="inner">
					<a href="/Login_v2/home.php" class="logo"><strong><font size="10">N E E V</font></strong> </a>
					<nav id="nav">
						<a href="/Login_v2/logout.php">	<button type="submit" name="login">
									Logout
							</button></a>
					</nav>
					<a href="#navPanel" class="navPanelToggle"><span class="fa fa-bars"></span></a>
				</div>
			</header>

		<!-- Banner -->
			<section id="banner">
				<div class="inner">
					<header>
						<h1>Subjects</h1>

					</header>

					<div class="flex ">

						<div>
							<span class="icon fa-superscript"></span>
							<h3><a class="click" id="math" onclick='run("<?php echo $class ?>",this.id)'>Mathematics</a></h3>

						</div>

						<div>
							<span class="icon fa-quote-right"></span>
							<h3><a class="click" id="eng" onclick='run( "<?php echo $class ?>",this.id)'>English</a></h3>

						</div>
					</div>

</div>
          <footer>
          <br>
          </br>
          <br>
        </br>
          </footer>


		<!-- Scripts <a href="#" class="button"></a> -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/skel.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>
      <script src="../navigate.js"></script>

	</body>
</html>
