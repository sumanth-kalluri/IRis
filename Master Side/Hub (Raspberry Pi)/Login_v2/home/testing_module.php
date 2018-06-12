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
						<p>
						<?php

						// Display message about account verification link only once
						if ( isset($_SESSION['message']) )
						{
								echo $_SESSION['message'];

								// Don't annoy the user with more messages upon page refresh
								unset( $_SESSION['message'] );
						}

						?>
						</p>
					</header>

					<div class="flex ">
            <div class="dropdown">
       <button id="1" onclick="myFunctionTest(this.id)" class="dropbtn">I</button>
     </div>
     <div class="dropdown">
       <button id="2" onclick="myFunctionTest(this.id)" class="dropbtn">II</button>
     </div>
     <div class="dropdown">
       <button id="3" onclick="myFunctionTest(this.id)" class="dropbtn">III</button>

     </div>
     <div class="dropdown">
       <button id="4" onclick="myFunctionTest(this.id)" class="dropbtn">IV</button>
     </div>
					</div>



        <div class="flex ">
          <div class="dropdown">
      <button id="5" onclick="myFunctionTest(this.id)" class="dropbtn">V</button>
      </div>
    <div class="dropdown">
      <button id="6" onclick="myFunctionTest(this.id)" class="dropbtn">VI</button>
      </div>
    <div class="dropdown">
      <button id="7" onclick="myFunctionTest(this.id)" class="dropbtn">VII</button>
      </div>
    <div class="dropdown">
      <button id="8" onclick="myFunctionTest(this.id)" class="dropbtn">VIII</button>
      </div>


    </div></div>
        <footer>
        <br>
        </footer>

			</section>


		<!-- Scripts <a href="#" class="button"></a> -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/skel.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>

  <script src="../home/navigate.js"></script>
	</body>
</html>
