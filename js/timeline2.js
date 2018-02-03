/**
 * Created by rainer on 29.03.2017.
 * https://developers.google.com/chart/interactive/docs/gallery/gauge#configuration-options
 */

function initChartTL() {
    var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('chart_timeline'));
    
    var options = {
        width: 800,
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

    function drawTimeLine() {
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
    drawTimeLine();
    setInterval(drawTimeLine, 60000);
}
//google.load('visualization', '1', {packages: ['gauge'], callback: initChart});

google.charts.load('current', {'packages': ['annotatedtimeline']});
google.charts.setOnLoadCallback(initChartTL);

