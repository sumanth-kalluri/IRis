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
		header("location: /login_v2/index.php?error=You must log in before viewing your profile page!");
}


?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>N E E V</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Load c3.css -->
    <link href="stylesheets/c3.css" rel="stylesheet" type="text/css">
    <link rel='stylesheet' href='stylesheets/style.css' />
    <link rel='stylesheet' href='stylesheets/bootstrap.min.css' />
    <link rel='stylesheet' href='stylesheets/font-awesome.min.css' />


		<link rel="stylesheet" href="/Login_v2/assets/css/main.css" />

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
        <nav id="nav">
          <a href="/Login_v2/logout.php">	<button type="submit" name="login">
                Logout
            </button></a>
        </nav>
      </div>
    </header>

		<!-- Banner -->
			<section id="banner">
				<div class="inner">
          <div class="flex">
            <div class="3u 12u$(small)">
            <form name="neevClinic"  >
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
        </select><br>
        <select name="sub" >
          <option value="">-SUBJECT-</option>
          <option value="math">Mathematics</option>
          <option value="english">English</option>
        </select>
        <br>
        <select id="topics" name="topic" onclick="isValid();">
          <option value="">-TOPICS-</option>

        </select>
        <br>
        <input type="text" name="sid" onkeypress="return !(event.charCode < 48 || event.charCode >= 58)" placeholder="Student Roll No."><br>

        <input type="text" onkeypress="return !(event.charCode < 48 || event.charCode >= 58)" name="testID" placeholder="Test Number"><br>

  <button type="button" onclick="val();"> Submit </button>
  </form>
</div>


<div id="chart" class="8u 12u$(large)">

</div>

</div>
<hr>
<br>


    <div id="xyzheader" class="table-wrapper">


                      </div>
                      <table id="tab" class="alt">

                      </table>
</div>
</section>




			<script src="/Login_v2/assets/js/jquery.min.js"></script>
			<script src="/Login_v2/assets/js/skel.min.js"></script>
			<script src="/Login_v2/assets/js/util.js"></script>
			<script src="/Login_v2/assets/js/main.js"></script>
      <script>
      var cls="";
      var subj="";
      function isValid(){

          var cl=document.forms["neevClinic"]["class"].value;
          var sub=document.forms["neevClinic"]["sub"].value;
          if(cls!=cl  || subj!=sub){
          var path="reports/"+cl+"/"+sub+"/";

          var el=$("#topics");
          el.empty();
          appendOptions(path);
          cls=cl;
          subj=sub;
        }
    }
        function appendOptions(path){
          var res=[];

          $.post('script.php', { p: path }, function(result) {
            result=result.slice(5,result.length-1);
            res=result.split(" ");
            var em="";
            $("#topics").append("<option value="+em+">-TOPICS-</option>");
            for(var i=0;i<res.length;i++){
              //add+="<option value="+res[i]+">"+res[i]+"</option>";
              $("#topics").append("<option value="+res[i]+">"+res[i]+"</option>");
            }
            });
        }
      function val(){
        var cl=document.forms["neevClinic"]["class"].value;
        var sub=document.forms["neevClinic"]["sub"].value;
        var top=document.forms["neevClinic"]["topics"].value;
        var sid=document.forms["neevClinic"]["sid"].value;
        var test=document.forms["neevClinic"]["testID"].value;
        if(cl==""){
          alert("Please select a class");
        }else{
          if(sub==""){
            alert("Please choose a valid Subject");
          }else{
            if(top==""){
              alert("Please choose a valid Topic");
            }else{
              if(sid==""){
                alert("Please enter a valid student ID");
              }else{
                if(test==""){
                  alert("Please enter a valid test number");
                }else{
                  var path = "reports/"+cl+"/"+sub+"/"+top+"/"+test+".csv";
                  var e_path="reports/"+cl+"/"+sub+"/"+top+"/map_"+test+".csv";
                  parseData(createGraph,path,e_path,sid);
                }
              }
            }
          }
        }
      }
        /*
         * Parse the data and create a graph with the data.
         */
        function parseData(createGraph,csv,e_path,id) {
        	Papa.parse(csv, {
        		download: true,
        		complete: function(results) {
        			createGraph(results.data,e_path,id);
        		}
        	});
        }
        function parseCSV(createTable,csv,errors) {
        	Papa.parse(csv, {
        		download: true,
        		complete: function(results) {
        			createTable(results.data,errors);
        		}
        	});
        }
        function createTable(data,errors){
          var names=[];
          for (var i=0;i<data.length;i++){
            for(var j=0;j<errors.length;j++){
              if(errors[j]==data[i][3]){
                  names.push( data[i][5]);
              }
            }
          }
          console.log(names);
          var el=$("#tab");
          el.empty();
          el=$("#xyzheader");
          el.empty();
          var tb="";

          for(var i=0;i<names.length;i++){

              tb+="<tr><td><h3>"+errors[i]+"</h3></td><td><h3>"+names[i]+"</h3></td></tr>";
          }
            $('#xyzheader').prepend("<h1> Error Map</h1>");
            $("table").append(tb);

        }


        function createGraph(data,e_path,id) {


        	var st_rec = data[id];
        	var resp = ["Resonse"];
        	var errorCode = ["errorCode"];
        	for (var i = 1; i < st_rec.length; i++) {
        				if(i%2!=0){
        				resp.push(st_rec[i]);
        				}else {
        				errorCode.push(st_rec[i]);
        				}

        		//Err.push(data[1][i]);
        	}
        	var correct=0,total=errorCode.length-1,attempted=total;
        	for (var i=1;i<errorCode.length;i++){
        		if (errorCode[i]=="0"){
        			correct++;
        		}
        		else if (errorCode[i]=="x"){
        			attempted--;
        		}

        	}
        	var visited=["isvisited"];
        	var errors = ["errors"];
        	var countErrors = ["number_errors"];
        for (var i=1;i<total+1;i++){
        	visited[i]=0;
        }
        var k=1;
        for (var i=1;i<total+1;i++){
        	if(visited[i]==0){
        		errors.push(errorCode[i]);
        		countErrors[k]=1;
        		for (var j=i+1;j<total+1;j++){
        				if(errorCode[i]==errorCode[j]){
        					visited[j]=1;
        					countErrors[k]++;
        				}

        		}
        		k++;
        	}
        }
        var index=-1,ind=-1;
        for (var i=1;i<k;i++){
        	if(errors[i]=="x")
        		index=i;
          if(errors[i]=="0")
              ind=i;
        }
        if(index!=-1){
        errors.splice(index,1);
            countErrors.splice(index,1);
      }

      if(ind!=-1){
        errors.splice(ind,1);
        countErrors.splice(ind,1);
      }

        //countErrors.splice(1,1);
        errors.splice(0,1);
        parseCSV(createTable,e_path,errors);

          //console.log(correct);
          console.log(errors);
        	console.log(countErrors);
        	console.log(resp);
        	console.log(errorCode);

        	var chart = c3.generate({
        		bindto: '#chart',
        		data: {
        	        columns: [
        	        countErrors
        				],
        				type: 'bar',
        				colors: {
        					 number_errors: '#6cc091'
                }
        },
          axis: {
                  y:{
                    tick: {
                      format: function (d) {
                              return (parseInt(d) == d) ? d : null;
                          }
                        }
                  },
        	        x: {
        	            type: 'category',
        	            categories: errors,
        	            tick: {
        	            	multiline: false,
        								}
        	        }
        	    },
        	    zoom: {
                	enabled: false
            	},
        	    legend: {
        	        position: 'bottom'
        	    }
        	});

          $('#chart').append("<br><h3>Correct Answers: "+correct+", Attempted: "+attempted+", Total Number of questions: "+total+"</h3><br>");

        }


      </script>
	</body>
</html>
