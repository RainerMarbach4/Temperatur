/**
 * Created by rainer on 29.03.2017.
 * https://developers.google.com/chart/interactive/docs/gallery/gauge#configuration-options
 */

google.charts.setOnLoadCallback(drawChart);
function drawChart() {
    var data = new google.visualization.DataTable();
    data.addColumn('datetime', 'Date');
    data.addColumn('number', 'Sold Pencils');
    data.addRows([
        [new Date(2008, 1 ,1), 30000],
        [new Date(2008, 1 ,2), 14045],
        [new Date(2008, 1 ,3), 55022],
        [new Date(2008, 1 ,4), 75284],
        [new Date(2008, 1 ,5), 41476],
        [new Date(2008, 1 ,6), 33322]
    ]);

    var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('chart_timeline'));
    chart.draw(data, {displayAnnotations: true});
}