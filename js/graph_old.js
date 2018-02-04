let barGraph ;

let sensorDayTimeFormat = "HH:mm";

$(document).ready(function(){
	let ctx = $("#mycanvas");

	barGraph = new Chart(ctx, {
		type: 'line',
		data: {
				labels: [],
				datasets : [
					{
						label: 'M4',
						backgroundColor: window.chartColors.red,
						borderColor: window.chartColors.red,
						fill: false,
						data: [],
                        pointRadius: 0,
                        lineTension: 0,
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
            animation: {
                duration: 0,
            },
            hover: {
                animationDuration: 0,
            },
            responsiveAnimationDuration: 0,
			scales: {
				xAxes: [{
					type: 'time',
					time: {
						parser: sensorDayTimeFormat,
						unit: 'minute',
						stepSize: 60,
						displayFormats: {
							minute: sensorDayTimeFormat,
						}
					}
				}]
			}
		}
			});
	updateGraph();
	setInterval(updateGraph, 60000);
});

function updateGraph() {
	let data = {
        field: 1,
        wert: 9,
        sensor: true,
	};
	if(barGraph.data.labels.length !== 0) {
        data.lasttimestamp = barGraph.data.labels[barGraph.data.labels.length - 2].format(sensorDayTimeFormat);
	} else {
		data.lasttimestamp = "00:00";
	}
    $.ajax({
        url: "./sensor/sensor-tag2.php",
		data,
        method: "GET",
        success: function(data) {
            barGraph.data.labels = [];
            barGraph.data.datasets[0].data = [];

            let minTime = moment();

            for(let i in data) {
                let iTime = moment(data[i].zeit, sensorDayTimeFormat);
                if(iTime.isBefore(minTime)) {
                    minTime = iTime;
                }
                barGraph.data.labels.push(iTime);
                barGraph.data.datasets[0].data.push(data[i].value);
            }

            minTime.minute(0);
            barGraph.options.scales.xAxes[0].time.min = minTime;
            barGraph.data.labels.push(moment("24:00", sensorDayTimeFormat));

            barGraph.update();
        },
        error: function(data) {
            console.log(data);
        }
    });
}