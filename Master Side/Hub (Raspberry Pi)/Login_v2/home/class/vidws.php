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
$choice="vid";
if(isset($_GET["cls"])){
  $class = $_GET["cls"] ;
}
if(isset($_GET["sub_id"])){
  $sub = $_GET["sub_id"] ;
}
if(isset($_GET["choice"])){
  $choice = $_GET["choice"] ;
}
if(isset($_GET["topic"])){
  $topic = $_GET["topic"] ;
}
$dir='cl/'.$class.'/'.$sub.'/'.$topic.'/'.$choice;
$file  = array() ;
$count = 0 ;
if(scandir($dir)!=FALSE){  $file = scandir($dir)  ;
$count = count($file) ;
$count=$count - 2;
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
        var choice = "<?php echo $choice ;?>";
        var topic="<?php echo $topic;?>";
        var noOfFlex=Math.ceil(cnt/3);


        $("header > h1").text(topic);
        function showVid(){
          for(var i=0;i<noOfFlex;i++){
            var dv="<div class='flex '>";
            for(var j=0;j<3;j++){
              if((i*3+j)<cnt){
                var lk=list[i*3+j];
                var link="cl/"+cls+"/"+sub+"/"+topic+"/"+choice+"/"+lk.slice(0,-4);
                var noDown="nodownload";
                dv+="<div><video width='240' height='180' controls controlsList="+noDown+"><source src='"+link+".mp4' type='video/mp4'><source src='"+link+".ogg' type='video/ogg'></video><br>";
                dv+="<h3>Lesson "+(i*3+j+1)+"</h3></div>";
              }
            }
            dv+="</div>";
            $("#banner .inner").append(dv);
          }
        }
        function showWs(){
          var tb="";
          for(var i=0;i<cnt;i++){
              var link=list[i];
              tb+="<tr><td><a href='/Login_v2/web/viewer.php?file=/Login_v2/home/class/cl/"+cls+"/"+sub+"/"+topic+"/"+choice+"/"+link+"'>"+"Worksheet "+(i+1)+"</a></td></tr>";
          }

          $("table").append(tb);
          $('#banner .inner').append("<br><br>");
        }
        if(choice==="vid")
          showVid();
        else {
          showWs();
        }
      });

      </script>
	</body>
</html>
