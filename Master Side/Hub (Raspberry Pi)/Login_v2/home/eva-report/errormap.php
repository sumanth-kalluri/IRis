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
  if(isset($_GET['path'])){
    $path=$_GET['path'];
  }
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
		<link rel="stylesheet" href="/Login_v2/assets/css/main.css" />
    <link rel='stylesheet' href='stylesheets/style.css' />
    <link rel='stylesheet' href='stylesheets/bootstrap.min.css' />
    <link rel='stylesheet' href='stylesheets/font-awesome.min.css' />

    <!-- Load c3.css -->
    <link href="stylesheets/c3.css" rel="stylesheet" type="text/css">

    <!-- Load d3.js and c3.js -->
    <script src="javascripts/d3.v3.min.js" charset="utf-8"></script>
    <script src="javascripts/c3.min.js"></script>

    <!-- Load papaparse.js -->
    <script src="javascripts/papaparse.min.js"></script>
	</head>
	<body>

		<!-- Header -->
			<header id="header">
				<div class="inner">
					<a href="/Login_v2/home.php" class="logo"><strong><font size="10">N E E V</font></strong> </a>

						<a href="/Login_v2/logout.php">	<button type="submit" name="login">
									Logout
							</button></a>


				</div>
			</header>

		<!-- Banner -->
			<section id="banner">
				<div class="inner">
					<header>
						<h1>Error Map </h1>

					</header>

					<div class="flex ">
            <div class="table-wrapper">
                                <table id="tab">
                                  <script>
                                    var path=<? echo $path ?>;
                                    parseData(createTable,path);
                                  </script>
                                </table>
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
			<script src="/Login_v2/assets/js/jquery.min.js"></script>
			<script src="/Login_v2/assets/js/skel.min.js"></script>
			<script src="/Login_v2/assets/js/util.js"></script>
			<script src="/Login_v2/assets/js/main.js"></script>
      <script>
      function parseData(createTable,path) {
        Papa.parse(path, {
          download: true,
          complete: function(results) {
            createTable(results.data);
          }
        });
      }
      function createTable(data){

      }

      </script>
	</body>
</html>
