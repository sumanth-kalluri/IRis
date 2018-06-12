/*
 * Parse the data and create a graph with the data.
 */
function parseData(createGraph) {
	Papa.parse("LCM-HCF.csv", {
		download: true,
		complete: function(results) {
			createGraph(results.data);
		}
	});
}

function createGraph(data) {
	var id=5;
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
	var countErrors = ["Number_errors"];
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
var index;
for (var i=1;i<k;i++){
	if(errors[i]=="x")
		index=i;
}
errors.splice(index,1);
countErrors.splice(index,1);
errors.splice(1,1);
countErrors.splice(1,1);
errors.splice(0,1);
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
					Number_errors: '#6cc091'
        }
},
  axis: {
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
	setTimeout(function () {
	    chart.load({
	        columns: [
	            countErrors
	        ]
	    });
	}, 1000);
}

parseData(createGraph);
