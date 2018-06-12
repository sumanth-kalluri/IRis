<?php
session_start();
if (isset($_SESSION['u_id'])) {
  $first_n = $_SESSION['u_first'];
  $last_n = $_SESSION['u_last'];
  $Email = $_SESSION['u_email'];
  $isschool = $_SESSION['is_school'];
}
else {
    $_SESSION['message'] = "You must log in before viewing your profile page!";
		header("location: index.php?error=You must log in before viewing your profile page!");
}
$code = "Hehehehe" ;
if(isset($_GET["code"])){  $code = $_GET["code"] ;    }
if(strcmp("error",$code) == 0){
  $message = "Invalid Details";
echo "<script type='text/javascript'>alert('$message');</script>";
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

					<div class="flex">
            <form name="neevClinic" onsubmit="return validate()" action="toperror.php" method="post">
        <select name="class" >
          <option value="">-CLASS-</option>
          <option value="1">I</option>
          <option value="2">II</option>
          <option value="3">III</option>
          <option value="4">IV</option>
          <option value="5">V</option>
          <option value="6">VI</option>
          <option value="7">VII</option>
          <option value="8">VIII</option>
        </select><br><br>
        <input type="text" name="sid" onkeypress="return !(event.charCode < 48 || event.charCode >= 58)" placeholder="Student ID"><br><br>
        <input type="text" onkeypress="return !(event.charCode < 48 || event.charCode >= 58)" name="testID" placeholder="Test Number"><br>
<br>
<h3>Subject:</h3>

                <input type="radio" id="maths" value="maths" name="sub">
                <label for="maths">Mathematics</label>
                <input type="radio" id="english" value="english" name="sub">
                <label for="english">English</label><br><br>
  <input type="submit">
  </form>


					</div>
</div>
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/skel.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>
      <script>
      function validate(){
        var cl=document.forms["neevClinic"]["class"].value;
        var sid=document.forms["neevClinic"]["sid"].value;
        var sub=document.forms["neevClinic"]["sub"].value;
        if(cl==""){
          alert("Please select a class");
          return false;
        }
        if(sid==""){
          alert("Please enter a valid Student ID");
          return false;
        }
        if(sub==""){
          alert("Please choose a subject");
          return false;
        }
        return true;
      }
	
      </script>
	</body>
</html>
