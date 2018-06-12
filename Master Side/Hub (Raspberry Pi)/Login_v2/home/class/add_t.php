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
  if (isset($_GET['test'])){
  $topic=$_GET['topic'];
  $class=$_GET['cls'];
  $sub=$_GET['sub_id'];
  $test = $_GET['test'];
  $row = 1;
  $data_encrypted=array();
  //$data_en;
  if (($handle = fopen("cl/".$class."/".$sub."/".$topic."/".$test.".csv", "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $num = count($data);
          //echo "<p> $num fields in line $row: <br /></p>\n";
          $row++;
          for ($c=0; $c < $num; $c++) {

              $data_encrypted[$row-1][$c]=$data[$c];
            //  echo $data_en[$c] . "<br />\n";
          }
      }
      fclose($handle);
  }
  if(($fp = fopen("/Login_v2/currentTest/en_".$class."_".$sub."_".$topic."_".$test.".csv",'w'))!=FALSE){
echo "<h3> saved file <h3>";
  foreach ($data_encrypted as $fields) {
    // code...
    fputcsv($fp,$fields);
  }
fclose($fp);
}

// If neccessaey to download the encrypted file in user defined download directory, uncomment the below commented code
//$file="tests/cl1/math/add/en_".$test.".csv";
//echo "<a href=$file >Start Test Now!</a> ";
//header("Content-type:text/csv");
//header("Content-Disposition:attachment;filename='test.csv'");
//readfile($file);
  }
}
else {

    $_SESSION['message'] = "You must log in before viewing your profile page!";
		header("location: /Login_v2/index.php?error=You must log in before viewing your profile page!");
}
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>N E E V</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="/Login_v2/assets/css/main.css" />
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
						<h1>Tests </h1>

					</header>

          <div class="flex flex-2">
            <ul style="list-style-type:square">
            <h3> <li><a href="/Login_v2/web/viewer.php?file=/Login_v2/home/class/pdfFiles/int.pdf">PDF Test1</a></li></h3>
            <h3> <li><a href="add_t.php?test=1">Test 1</a></li></h3>


            </ul>
        </div>


          </div>

					<footer>
					<br>
        </br>
        <br>
        <br>
					</footer>

			</section>


		<!-- Scripts <a href="#" class="button"></a> -->
			<script src="/Login_v2/assets/js/jquery.min.js"></script>
			<script src="/Login_v2/assets/js/skel.min.js"></script>
			<script src="/Login_v2/assets/js/util.js"></script>
			<script src="/Login_v2/assets/js/main.js"></script>

	</body>
</html>
