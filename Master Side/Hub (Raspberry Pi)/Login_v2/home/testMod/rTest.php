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
$topic="";
$class = 1 ;
$sub = "math";

if(isset($_GET["cls"])){
  $class = $_GET["cls"] ;
}
if(isset($_GET["sub_id"])){
  $sub = $_GET["sub_id"] ;
}

if(isset($_GET["topic"])){
  $topic = $_GET["topic"] ;
}
$dir='../class/cl/'.$class.'/'.$sub.'/'.$topic.'/test'.'/';
$file  = array() ;
$count = 0 ;
if(scandir($dir)!=FALSE){  $file = scandir($dir)  ;}
$count = count($file) ;
$count=$count - 2;
//if (isset($_SESSION['u_id'])) {
  // Makes it easier to read
if (isset($_GET['test'])){
  $topic=$_GET['topic'];
  $class=$_GET['cls'];
  $sub=$_GET['sub_id'];
  $test = $_GET['test'];
  $row = 1;
  $data_encrypted=array();
  //$data_en;
  if (($handle = fopen("../class/cl/".$class."/".$sub."/".$topic."/test"."/".$test.".csv", "r")) !== FALSE) {
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
  $pathInPieces = explode('\home\testMod',dirname(__FILE__));
  if(($fp = fopen($pathInPieces[0]."/currentTest/en_".$class."_".$sub."_".$topic."_".$test.".csv",'w'))!=FALSE){

  foreach ($data_encrypted as $fields) {
    // code...
    fputcsv($fp,$fields);
  }
fclose($fp);
}
header("location: loader.php");
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
      tr{
        font-size: 1.5em;
      }
      #crash{
        height:100vw;
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
						<h1></h1>
					</header>
          <div class="table-wrapper"><table></table></div>

  </div>

<br>
			</section>

		<!-- Scripts <a href="#" class="button"></a> -->
			<script src="/Login_v2/assets/js/jquery.min.js"></script>
			<script src="/Login_v2/assets/js/skel.min.js"></script>
			<script src="/Login_v2/assets/js/util.js"></script>
			<script src="/Login_v2/assets/js/main.js"></script>
      <script>
      $(document).ready(function(){
        var cls="<?php echo $class;?>";
        var sub="<?php echo $sub;?>";
        var cnt="<?php echo $count ;?>";
        var list =<?php echo json_encode($file);?>;
        list.shift();
        list.shift();
        if(!Array.isArray(list)||!list.length){
          $("div").empty();
          $("#banner").append("<div id='crash'></div>");
          alert("Directory Empty");
          window.history.back();
        }
        var topic="<?php echo $topic;?>";


        $("header > h1").text(topic);
          var tb="";
          for(var i=0;i<cnt;i++){
              var link=list[i];
              tb+="<tr><td><a href='rTest.php?cls="+cls+"&sub_id="+sub+"&topic="+topic+"&test="+(i+1)+"'>"+"Test "+(i+1)+"</a></td></tr>";
          }
          $("table").append(tb);
          $('#banner .inner').append("<br><br>");

      });

      </script>
	</body>
</html>
