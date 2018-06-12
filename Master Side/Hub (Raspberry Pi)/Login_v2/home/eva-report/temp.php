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
					<nav id="nav">
						<a href="../logout.php">	<button type="submit" name="login">
									Logout
							</button></a>
					</nav>

				</div>
			</header>

		<!-- Banner -->
			<section id="banner">
				<div class="inner">
					<header>
						<h1> CLASSES </h1>
					</header>

          <div class="flex ">

						<div>
              <span class="fa-stack fa-3x">
  <i class="fa fa-circle-o fa-stack-2x"></i>
  <strong class="fa-stack-1x"><a href="subjects.php?Class=1">1</a></strong>
</span>


						</div>

						<div>
              <span class="fa-stack fa-3x">
  <i class="fa fa-circle-o fa-stack-2x"></i>
  <strong class="fa-stack-1x"><a href="subjects.php?Class=2">2</a></strong>
</span>


						</div>

						<div>
              <span class="fa-stack fa-3x">
    <i class="fa fa-circle-o fa-stack-2x"></i>
    <strong class="fa-stack-1x"><a href="subjects.php?Class=3">3</a></strong>
  </span>

						</div>
						<div>
              <span class="fa-stack fa-3x">
    <i class="fa fa-circle-o fa-stack-2x"></i>
    <strong class="fa-stack-1x"><a href="subjects.php?Class=4">4</a></strong>
  </span>

						</div>
					</div>



        <div class="flex ">

          <div>
            <span class="fa-stack fa-3x">
    <i class="fa fa-circle-o fa-stack-2x"></i>
    <strong class="fa-stack-1x"><a href="subjects.php?Class=5">5</a></strong>
  </span>


          </div>

          <div>
            <span class="fa-stack fa-3x">
  <i class="fa fa-circle-o fa-stack-2x"></i>
  <strong class="fa-stack-1x"><a href="subjects.php?Class=6">6</a></strong>
</span>


          </div>

          <div>
            <span class="fa-stack fa-3x">
  <i class="fa fa-circle-o fa-stack-2x"></i>
  <strong class="fa-stack-1x"><a href="subjects.php?Class=7">7</a></strong>
</span>

          </div>
          <div>
            <span class="fa-stack fa-3x">
    <i class="fa fa-circle-o fa-stack-2x"></i>
    <strong class="fa-stack-1x"><a href="subjects.php?Class=8">8</a></strong>
  </span>

          </div>
  </div>

        <footer>
        <br>
        </footer>

			</section>


		<!-- Scripts <a href="#" class="button"></a> -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/skel.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>
    <!--  <script>
            function myFunction(clicked_id) {

              var dropdowns = document.getElementsByClassName("dropdown-content");
              var i;
              for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                  openDropdown.classList.remove('show');
                }
              }
              document.getElementById("sub" + clicked_id).classList.toggle("show");
            }

            window.onclick = function(event) {
              if (!event.target.matches('.dropbtn')) {

                var dropdowns = document.getElementsByClassName("dropdown-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                  var openDropdown = dropdowns[i];
                  if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                  }
                }
              }
            }

          </script>
-->
  <script src="../home/navigate.js"></script>
	</body>
</html>
