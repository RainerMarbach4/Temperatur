$(document).ready(function(){
	$.ajax({
		url: "http://localhost/sensor/sensor-tag3.php?field=1&wert=9&sensor",
		method: "GET",
		success: function(data) {
			console.log(data);
			var player = [];
			var score = [];

			for(var i in data) {
				player.push("Z: " + data[i].zeit);
				score.push(data[i].value);
			}

			var chartdata = {
				labels: player,
				datasets : [
					{
						label: 'Temperaturen',
						backgroundColor: 'rgba(200, 200, 200, 0.75)',
						borderColor: 'rgba(200, 200, 200, 0.75)',
						hoverBackgroundColor: 'rgba(200, 200, 200, 1)',
						hoverBorderColor: 'rgba(200, 200, 200, 1)',
						data: score
					}
				]
			};

			var ctx = $("#mycanvas");

			var barGraph = new Chart(ctx, {
				type: 'bar',
				data: chartdata
			});
		},
		error: function(data) {
			console.log(data);
		}
	});
});