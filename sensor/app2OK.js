$(document).ready(function(){
	$.ajax({
		url: "./sensor/sensor-tag.php?field=1&wert=9&sensor",
		method: "GET",
		success: function(data) {
			console.log(data);
			var zeit = [];
			var score = [];
			var zeit2 = [];
			var score2 = [];

			for(var i in data) {
				//zeit.push("Z: " + data[i].zeit);
				zeit.push(data[i].zeit);
				score.push(data[i].value);
				zeit2.push(data[i].zeit);
				score2.push(data[i].value * (.95+Math.random()/22));
			}

			var chartdata = {
				labels: zeit,
				datasets : [
					{
						label: 'M4',
						backgroundColor: window.chartColors.red,
						borderColor: window.chartColors.red,
						fill: false,
						data: score
					},{
						label: '1.Terasse',
						backgroundColor: window.chartColors.green,
						borderColor: window.chartColors.green,
						fill: false,
						data: score2
					}
				],
				options: {
					responsive: false,
					maintainAspectRatio: false
				}
			};

			var ctx = $("#mycanvas");

			var barGraph = new Chart(ctx, {
				type: 'line',
				data: chartdata
			});
		},
		error: function(data) {
			console.log(data);
		}
	});
});