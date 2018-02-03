/**
 * Created by david on 02.01.2017.
 */

function ChartControl(name, humanName) {
    this.name = name;
    this.humanName = humanName !== undefined ? humanName : this.name;
    this.id = "chart" + this.name;
    this.loadingId = this.id + "Loading";
    this.chartTimes = [];
    this.chartData = [];
    this.chart = undefined;
    this.loaded = false;
    this.loading = false;
    this.userTimes = {"1 Day": "P1D", "12 Hour": "PT12H", "2 Hour": "PT2H", "1 Hour": "PT1H", "30 Min": "PT30M", "5 Min": "PT5M", "1 Min": "PT1M"};
    this.lastButton = undefined;

    this.init = function () {
        var dataCharts = $("#dataCharts");
        var html = '<div class="page-header"><h1>{{name}}{{btnGroup}}</h1></div><div class="row">' +
            '<div id="' + this.loadingId + '"><i class="fa fa-refresh fa-spin"></i>' +
            '<canvas id="{{id}}"></canvas></div></div>';

        var btnGroup = "<div id='" + this.id + "Btns' class='btn-group' style='float: right'></div>";

        html = html.replace("{{name}}", this.humanName).replace("{{id}}", this.id).replace("{{btnGroup}}", btnGroup);

        dataCharts.append(html);
        this.setLoading(true);

        var btnGroupElement = $("#" + this.id + "Btns")[0];
        var min5button;
        for(var i in this.userTimes) {
            var btn = document.createElement("button");
            btn.appendChild(document.createTextNode(i));
            btn.classList.add("btn");
            btn.classList.add("btn-default");
            this.setBtnOnclick(btn, i);
            if(i === "5 Min") {
                min5button = btn;
            }
            btnGroupElement.appendChild(btn);
        }

        var ctx = $("#" + this.id)[0];
        this.chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: this.chartTimes,
                datasets: [{
                    lineTension: 0,
                    label: this.humanName,
                    data: this.chartData,
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
                            suggestedMax: 25,
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

        this.setDisplayTime("PT5M", min5button);
    };

    this.setBtnOnclick = function (btn, index) {
        var thisRef = this;
        btn.onclick = function () {
            thisRef.setDisplayTime(thisRef.userTimes[index], btn);
        };
    };

    this.setDisplayTime = function(time, btn) {
        $(this.lastButton).removeClass("btn-primary");
        $(btn).addClass("btn-primary");
        this.lastButton = btn;
        this.chartTimes.length = 0;
        this.chartData.length = 0;

        this.setLoading(true);
        this.loaded = false;

        var thisRef = this;
        $.get("/getData.php?sensor=" + this.name + "&back=" + time, function(data) {thisRef.gotPhpData(data)});
    };


    this.addData = function(time, data) {
        if(time === undefined || data === undefined) {
            return;
        }
        if(this.loaded) {
            this.chartTimes.shift();
            this.chartTimes.push(time);
            this.chartData.shift();
            this.chartData.push(data);
            this.chart.update();
        } else {
            this.chartTimes.push(time);
            this.chartData.push(time);
        }
    };

    this.setLoading = function (loading) {
        if(loading) {
            $("#" + this.loadingId).removeClass("chartFinished").addClass("chartLoading");
        } else {
            $("#" + this.loadingId).removeClass("chartLoading").addClass("chartFinished");
        }
        this.loading = loading;
    };

    this.gotPhpData = function(gotData) {
        gotData = JSON.parse(gotData);
        gotData = gotData["data"];

        var tmpTimes = this.chartTimes.splice();
        var tmpData = this.chartData.splice();

        for(var i in gotData) {
            var d = i.replace(" ", "T");
            this.chartTimes.push(new Date(d));
            this.chartData.push(gotData[i][1]);
        }

        for(var i = 0; i < tmpTimes.length; i++) {
            this.addData(tmpTimes[i], tmpData[i]);
        }

        this.chart.update();
        this.setLoading(false);
        this.loaded = true;
    };

    this.init();

}