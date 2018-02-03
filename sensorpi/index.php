<?php
//------------------- Config und Funktionen einbinden --------------------------
require_once("config.php");
require_once("functions.php");

//------------------------ PHP Settings ----------------------------------------
ini_set('track_errors', 1);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set("memory_limit","64M");
ini_set("max_execution_time","30");
@ob_implicit_flush(true);
@ob_end_flush();
$_SELF=$_SERVER['PHP_SELF'];

if (!file_exists($DBfile)) { echo "<center>Database missing!</center>\n"; _exit(); }
$SelectedNodes = isset($_POST["nodeID"]) ? $_POST["nodeID"] : "";

//------------------------ Daten für Grafik holen ----------------------------------------
	//Aus selektierter Node DataTable für Chart generieren
if (!empty($SelectedNodes))
{
	$SQL="SELECT supplyV,temp,hum,datetime(time,'unixepoch') AS timestamp FROM werte WHERE nodeID='".$SelectedNodes."'";
	
	$db = db_con($DBfile);
	$q = db_query($SQL);

	//Zeilen Header definieren, werden im Chart angezeigt
	$data = "var data = new google.visualization.DataTable();\n"
			."data.addColumn('datetime', 'Timestamp');\n"
			."data.addColumn('number', 'Volt');\n"
			."data.addColumn('number', 'Feuchtigkeit');\n"
			."data.addColumn('number', 'Temperatur');\n\n"
			
			."data.addRows([\n";
	
	while ($res = $q->fetch(PDO::FETCH_ASSOC)) {
		
		$temp = (int)$res['temp'] / 100;
		$hum = (int)$res['hum'] / 100;
		$pwr = (int)$res['supplyV'] / 1000;
		$timestamp = $res['timestamp'];
		
		$data = $data."  [new Date('".$timestamp."'), ".$pwr.", ".$hum.", ".$temp."],\n";
	}
	$data =	$data."[new Date('2014-11-22 16:01:29'), 4.434, 59, 19.79]";
	$data = $data."]);\n";
}

?>

<!-- HTML & Java Script für das Chart -->

<html>
<head>
	<title>http://raspberry.tips - Temperatur Ausgabe</title>
	<link href="style.css" rel="stylesheet" type="text/css" />
	
	<!-- Load jQuery -->
    <script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
    
	<!-- Load Google JSAPI -->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>	
	<script type="text/javascript">
		google.load("visualization", "1", { packages: ["annotatedtimeline"] });
		google.setOnLoadCallback(drawChart);

		function drawChart() {
			<!-- Generierte Data Table integieren -->
			<?php echo $data; ?>
			
			<!-- Chart Optionen -->
			var options = {
				title: 'Raspberry.Tips Daten',
				backgroundColor: {stroke:'black', fill:'#f2f2f2', strokeSize: 0},
				displayZoomButtons: true,
				hAxis: { format: 'dd.MM. HH:mm' }				
			};

			<!-- Chart vom Typ  AnnotatedTimeLine generieren-->
			var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('chart_div'));
			chart.draw(data, options);
		}
	</script>
</head>
<body>

<?php

//----------------- Aktuelle Werte Ausgeben ------------------------------------
echo "<center>\n";
echo "<table border='0'>\n<tr>";

$db = db_con($DBfile);
$q = db_query("SELECT temp,place FROM werte WHERE id IN (SELECT MAX(id) FROM werte GROUP BY nodeID)");

while ($res = $q->fetch(PDO::FETCH_ASSOC)) {

	$temp = $res['temp'] / 100;
	$arrayTemp = explode(".", $temp);

	echo "<td>\n<div align='center'>\n";
	echo "	<h3>".$res['place']."</h3>\n";
	echo "	<div class='container'>\n";
	echo "		<div class='de'>\n";
	echo "			<div class='den'>\n";
	echo "			  <div class='dene'>\n";
	echo "				<div class='denem'>\n";
	echo "				  <div class='deneme'>\n";
	echo "						".$arrayTemp[0]."<span>".$arrayTemp[1]."</span>";
	echo "				  </div>\n";
	echo "				</div>\n";
	echo "			  </div>\n";
	echo "			</div>\n";
	echo "		</div>\n";
	echo "	</div>\n";
	echo "</div>\n</td>\n"  ;
}
unset($res);
echo "</tr>\n</table>\n";
echo "</center>\n";
//-----------------------------------------------------------------------------------

//----------------- Knoten zur Auswahl holen ------------------------------------
echo "<center>\n";
echo "<br/>\n";
echo "<br/>\n";
echo "<h3>Sensordaten anzeigen</h3>\n";
echo "<form action='' name='sensor' method='POST'>\n";
echo "<select name='nodeID'>";

$i=0;
$s=" ";
$MAXROW=10;

$db = db_con($DBfile);
$q = db_query("SELECT nodeID,place FROM werte WHERE 1 GROUP BY nodeID ORDER BY nodeID ASC");

while ($res = $q->fetch(PDO::FETCH_ASSOC)) {
	echo "<option value='".$res['nodeID']."'>".$res['place']."</option>'";
}

unset($FoundChecked);
unset($node_id);
unset($res);

echo "</select>\n<br><br>\n<input type='submit' value='Anzeigen'>\n</form>";
echo "</center>\n";
?>
<center>
<!-- Chart DIV -->
<div id="chart_div" style="width: 900px; height: 500px;"></div>
</center>
</body>
</html>

