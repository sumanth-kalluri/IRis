<?php
session_start();
// Check if user is logged in using the session variable
if (isset($_SESSION['u_id'])==FALSE) {

    $_SESSION['message'] = "You must log in before viewing your profile page!";
		header("location: index.php?error=You must log in before viewing your profile page!");
}
$testID = $_POST['testID']  ;
$sub = $_POST['sub']  ;
$path = "testReport/".$sub."/".$testID.".csv" ;

if(file_exists($path)==FALSE ){
  header("Location: neev_clinic.php?code=error");
   exit() ;
}
$idd = $_POST['sid'] ;
$err_code = array();
$err_count= array() ;
$top_error_code = array() ;
$ct = 0 ;

if (($handle = fopen("$path", "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

      $num = count($data);
      if($ct == 0){
        for ($c=1; $c < $num; $c++) {
            $err_code[$c-1] = $data[$c] ;
        }
        $ct = 1 ;
      }
        //echo "<p> $num fields in line $row: <br /></p>\n";
        if($data[0] == $idd ){
          for ($c=1; $c < $num; $c++) {
              $err_count[$err_code[$c-1]] = $data[$c] ;
          }
          arsort($err_count);
      $cnt = 0 ;
          foreach($err_count as $x => $x_value) {
            if($cnt>2){ break; }
    $top_error_code[$cnt] = $x ; $cnt = $cnt +1 ;
}

$er1=$top_error_code[0];
$er2=$top_error_code[1];
$er3=$top_error_code[2];
  $ct = -1 ;break ;
}
    }
    if($ct != -1 ){
      header("Location: neev_clinic.php?code=error");
       exit() ;
    }
    fclose($handle);
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
						<h1>Correction Videos</h1>
					</header>
					<div class="flex ">
						<div>
                <video width="240" height="180" controls>
                  <source class="er1_m" src="" type="video/mp4">
                  <source class="er1_o" src="" type="video/ogg">
                    Your browser does not support the video tag.
                </video>
                <br>
                <h3>Lesson 1</h3>
            </div>
          <div>
              <video width="240" height="180" controls>
                <source class="er2_m" src="" type="video/mp4">
                <source class="er2_o" src="" type="video/ogg">
                  Your browser does not support the video tag.
              </video>
              <br>
              <h3>Lesson 2</h3>
          </div>
        <div>
            <video width="240" height="180" controls>
              <source class="er3_m" src="" type="video/mp4">
              <source class="er3_o" src="" type="video/ogg">
                Your browser does not support the video tag.
            </video>
            <br>
            <h3>Lesson 3</h3>
        </div>
  </div>
					<footer>
					<br>
					</footer>
			</section>
		<!-- Scripts <a href="#" class="button"></a> -->
			<script src="/Login_v2/assets/js/jquery.min.js"></script>
			<script src="/Login_v2/assets/js/skel.min.js"></script>
			<script src="/Login_v2/assets/js/util.js"></script>
			<script src="/Login_v2/assets/js/main.js"></script>
	</body>
  <script>
  var er1 = "<?php echo $er1 ?>";
  var er2 = "<?php echo $er2 ?>";
  var er3 = "<?php echo $er3 ?>";
$(document).ready(function(){
    $(".er1_m").attr("src",er1+".mp4");
    $(".er1_o").attr("src",er1+".ogg");
    $(".er2_m").attr("src",er2+".mp4");
    $(".er2_o").attr("src",er2+".ogg");
    $(".er3_m").attr("src",er3+".mp4");
    $(".er3_o").attr("src",er3+".ogg");
    $("video").load();
    //alert(er1+er2+er3);

});
  </script>
</html>
