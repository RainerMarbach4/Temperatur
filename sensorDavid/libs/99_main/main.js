/**
 * Created by David on 11.10.2016.
 */

var wsuri;
if (document.location.origin == "file://") {
    wsuri = "ws://127.0.0.1:8080/ws";
} else {
    wsuri = (document.location.protocol === "http:" ? "ws:" : "wss:") + "//" +
        document.location.host.replace(":8081", "") + ":8080/ws";
}

var connection = new autobahn.Connection({
    url: wsuri,
    realm: "realm1"
});

var session;

function firstLetterUppercase(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

connection.onopen = function (sess, details) {
    session = sess;
    console.log("Connected");

    session.subscribe("main.save_data_update", dataUpdateCall);

    session.call("main.get_sensor_list").then(function (list) {
        if(!list instanceof Array || list.length == 0) {
            return;
        }
        //var dataChartDiv = $("#dataCharts")[0];
        var row = $("#quickDataRow")[0];
        var size = row.clientWidth/list.length;
        for(var a in list) {
            // Creatke Gauges
            var senName = list[a];
            var gauge = document.createElement("div");
            gauge.id = "gauge" + senName;
            gauge.style.width = size + "px";
            gauge.style.height = size + "px";
            gauge.style.display = "inline-block";
            row.appendChild(gauge);
            quickDataGauges[senName] = new JustGage({
                id: "gauge" + senName,
                min: 0,
                max: 40,
                decimals: senName.indexOf("pres") === -1,
                title: firstLetterUppercase(senName),
                relativeGaugeSize: true
            });
            session.subscribe("sensor.value." + senName, updateData);

            // Create charts



        }

        session.call("main.get_last_data").then(function (data) {
            for(var i in data) {
                updateData([[0, i, data[i]]]);
            }
        });

    });

};

function updateUpdateTimer(t) {
    if(Number(t) <= 0) {
        return;
    }
    session.call("main.change_sample_rate", [Number(t)]).then(function (data) {
        if(data != null && data["ok"]) {
            swal("Ok", "Sample Rate updated to " + data["new_time"], "success");
        } else {
            swal("Fail", "Something went wrong", "error")
        }
    })
}

var quickDataGauges = [];
var dataCharts = [];

function updateData(got) {
    // string.indexOf(substring) !== -1
    if(got[0][1].indexOf("pres") !== -1) {
        got[0][2] = Math.round(got[0][2] * 10);
    }
    quickDataGauges[got[0][1]].refresh(got[0][2]);
}


function dataUpdateCall(data) {
    var time = new Date(data[0]);
    data = data[1];
    /*times.shift();
    times.push(time);
    temps.shift();
    temps.push(data["temp_in"]);
    chart.update();/**/
    for(var i in data) {
        if(i in dataCharts) {
            dataCharts[i].addData(time, data[i]);
        }
    }
}/**/

var chart;
var times = [];
var temps = [];

$(document).ready(function () {
    dataCharts["temp_in"] = new ChartControl("temp_in", "Temperature Inside");
    dataCharts["temp_out"] = new ChartControl("temp_out", "Temperature Outside");
    dataCharts["light_full"] = new ChartControl("light_full", "Full Light");

    /*$.get("/getData.php?sensor=temp_in&back=PT15M", function (gotData) {
        gotData = JSON.parse(gotData);
        console.log(gotData);

        var data = gotData["data"];
        console.log(data);

        for(var i in data) {
            var d = i.replace(" ", "T");
            times.push(new Date(d));
            temps.push(data[i][1]);
        }

        //console.log(times);

        var ctx = $("#dataChart");
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: times,
                datasets: [{
                    lineTension: 0,
                    label: "t1",
                    data: temps,
                    borderWidth: 0,
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderColor: 'rgba(255,99,132,1)',
                    animation: 0,
                    pointRadius: 0
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            suggestedMax: 20,
                            suggestedMin: 0
                        }
                    }],
                    xAxes: [{
                        type: "time",
                        time: {
                            displayFormats: {
                                minute: 'h:mm a'
                            }
                        }
                    }]
                },
                animation: {
                    duration: 0
                }
            }
        });

        connection.open();

    });/**/

    connection.open();

});
