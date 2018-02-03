/**
 * Created by rainer on 29.03.2017.
 * https://developers.google.com/chart/interactive/docs/gallery/gauge#configuration-options
 */

function initChart() {
    var chart = new google.visualization.Gauge(document.getElementById('chart_div'));
    var options = {
        width: 600,
        height: 280,
        greenFrom: 10,
        greenTo: 30,
        redFrom: 30,
        redTo: 60,
        yellowColor: '#6495ed',
        yellowFrom: -30,
        yellowTo: 10,
        min: -30,
        max: 60,
        minorTicks: 5
    };

    function drawGauge() {
        $.getJSON('google/getData.php', function (json) {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Label');
            data.addColumn('number', 'Value');
            for (x in json) {
                data.addRow([x, json[x]]);
            }
            chart.draw(data, options);
        });
    }
    drawGauge();
    setInterval(drawGauge, 60000);
}
//google.load('visualization', '1', {packages: ['gauge'], callback: initChart});

google.charts.load('current', {'packages': ['gauge', 'annotatedtimeline']});
google.charts.setOnLoadCallback(initChart);

