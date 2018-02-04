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
		data.lasttimestamp = "00:00";x
	}
    $.ajax({
        url: "./sensor/sensor-tag2.php",
		data,
        method: "GET",
        success: function(data) {

        	for(let i in data) {
        		let mTime = moment(data[i].zeit, sensorDayTimeFormat);
        		if(mTime in barGraph.data.labels) {
        			// TODO update
				} else {
        			barGraph.labels.push(mTime);
        			barGraph.datasets[0].data.push(data[i].value);
				}
			}

			if(!barGraph.data.labels[barGraph.data.labels.length - 1].isSame(moment("24:00", 'minute'))) {
                barGraph.data.labels.push(moment("24:00", sensorDayTimeFormat));
			}

			let minTime = moment(barGraph.labels[0]);
        	minTime.minute(0);
        	barGraph.options.scale.xAxes[0].time.min = minTime;

            barGraph.update();
        },
        error: function(data) {
            console.log(data);
        }
    });
}