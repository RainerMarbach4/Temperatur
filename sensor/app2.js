$(document).ready(function(){
	var ctx = $("#mycanvas");
	
	var barGraph = new Chart(ctx, {
		type: 'line',
		data: {
			labels: [],
			datasets : [
				{
					label: 'M4',
					backgroundColor: window.chartColors.red,
					borderColor: window.chartColors.red,
					fill: false,
					data: []
				},{
					label: '1.Terasse',
					backgroundColor: window.chartColors.green,
					borderColor: window.chartColors.green,
					fill: false,
					data: []
				}
			],
			options: {
				responsive: false,
				maintainAspectRatio: false
			}
		},
		options: {
			scales: {
				xAxes: [{
					type: 'time',
					time: {
						parser: 'DD-MM-YYYY  HH:mm',
						unit: 'minute',
						stepSize: 60,
						displayFormats: {
							minute: 'HH:mm'
						}
					}
				}]
			}
		}
	});
	
	function updateGraph() {
	$.ajax({
		url: "./sensor/sensor-tag.php?field=1&wert=9&sensor",
		method: "GET",
		success: function(data) {
			barGraph.data.labels = [];
			barGraph.data.datasets[0].data = [];
			barGraph.data.datasets[1].data = [];
			
			let minTime = moment();

			for(var i in data) {
				let iTime = moment(data[i].zeit, 'DD-MM-YYYY  HH:mm')
				if(iTime.isBefore(minTime)) {
					minTime = iTime;
				}
				barGraph.data.labels.push(data[i].zeit);
				barGraph.data.datasets[0].data.push(data[i].value);
				barGraph.data.datasets[1].data.push(data[i].value * (.95+Math.random()/22));
			}
			
			minTime.minute(0);
			barGraph.options.scales.xAxes[0].time.min = minTime;
			
			barGraph.update();
		},
		error: function(data) {
			console.log(data);
		}
	});
}

	updateGraph();
	setInterval(updateGraph, 60000);
});