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
$sub = "math" ;
if(isset($_GET["cls"])){
  $class = $_GET["cls"] ;
}
else {
  echo "Invalid Id Access for class" ;
}
if(isset($_GET["sub_id"])){
  $sub = $_GET["sub_id"] ;
}
else {
  echo "Invalid Id Access for sub id" ;
}
  $dir    = '../class/cl/'.$class.'/'.$sub;
  $file  = array() ;
  if(scandir($dir)!=FALSE){ $file = scandir($dir,1)  ; }
  $count = count($file) ;
  $count = $count - 2 ;
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>N E E V</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="/Login_v2/assets/css/main.css" />
    <style>
      #crash{
        height:100vw;
      }
      tr{
        font-size: 1.5em;
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
					 <h1>Test Topics</h1>

					</header>

          <br><br>
          <div class="table-wrapper">
          										<table>

          										</table>
          									</div>



			</section>

			<script src="/Login_v2/assets/js/jquery.min.js"></script>
			<script src="/Login_v2/assets/js/skel.min.js"></script>
			<script src="/Login_v2/assets/js/util.js"></script>
			<script src="/Login_v2/assets/js/main.js"></script>
      <script src="../navigate.js"></script>

<script>
  $(document).ready(function(){

    var cls="<?php echo $class;?>";
    var sub="<?php echo $sub;?>";
    var cnt="<?php echo $count ;?>";
    var topics =<?php echo json_encode($file);?>;
    var dir = "<?php echo $dir ;?>";
    if(!Array.isArray(topics)||!(topics.length-2)){
      $("div").empty();
      $("#banner").append("<div id='crash'></div>");
      alert("Directory Empty");
      window.history.back();
    }
    $('input').on('click',function(){
      for(var i=0;i<cnt;i++){
        $("#"+i).attr('href',"rTest.php?cls="+cls+"&sub_id="+sub+"&topic="+topics[i]);
      }
    });

      var tb="";
      for(var i=0;i<cnt;i++){

          tb+="<tr><td><a id='"+i+"' href='rTest.php?cls="+cls+"&sub_id="+sub+"&topic="+topics[i]+"'>"+topics[i]+"</a></td></tr>";
      }

      $("table").append(tb);
  });

</script>
	</body>
</html>
