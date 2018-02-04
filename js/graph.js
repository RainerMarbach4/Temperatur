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

function momentGetIndexInArray(stack, needle) {
    for(let i = 0; i < stack.length; i++) {
        if(stack[i].isSame(needle, 'minute')) {
            return i;
        }
    }
	return -1;
}

function momentInArray(stack, needle) {
	return momentGetIndexInArray(stack, needle) !== -1;
}

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

            if(barGraph.data.labels.length < 1 || !momentInArray(barGraph.data.labels, moment("24:00", sensorDayTimeFormat))) {
                barGraph.data.labels.push(moment("24:00", sensorDayTimeFormat));
                barGraph.data.datasets[0].data.push(null);
            }

        	for(let i in data) {
        		let mTime = moment(data[i].zeit, sensorDayTimeFormat);
        		if(momentInArray(barGraph.data.labels, mTime)) {
        			let index = momentGetIndexInArray(barGraph.data.labels, mTime);
        			barGraph.data.datasets[0].data[index] = data[i].value;
				} else {
        			barGraph.data.labels.splice(-1, 0, mTime);
        			barGraph.data.datasets[0].data.splice(-1, 0, data[i].value);
				}
			}

			let minTime = barGraph.data.labels[0].clone();
        	minTime.minute(0);
            barGraph.options.scales.xAxes[0].time.min = moment("00:00", sensorDayTimeFormat);

            barGraph.update();
        },
        error: function(data) {
            console.log(data);
        }
    });
}