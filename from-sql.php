<?php
/**
 * This file loads content from four different data tables depending on the required time range.
 * The stockquotes table containts 1.7 million data points. Since we are loading OHLC data and
 * MySQL has no concept of first and last in a data group, we have extracted groups by hours, days
 * and months into separate tables. If we were to load a line series with average data, we wouldn't
 * have to do this.
 *
 * @param callback {String} The name of the JSONP callback to pad the JSON within
 * @param start {Integer} The starting point in JS time
 * @param end {Integer} The ending point in JS time
 * @param index {String} The symbol of the index
 */

// get the parameters

$callback = $_GET['callback'];
if (!preg_match('/^[a-zA-Z0-9_]+$/', $callback)) {
	die('Invalid callback name');
}

$start = @$_GET['start'];
if ($start && !preg_match('/^[0-9]+$/', $start)) {
	die("Invalid start parameter: $start");
}

$end = @$_GET['end'];
if ($end && !preg_match('/^[0-9]+$/', $end)) {
	die("Invalid end parameter: $end");
}

if (!$end) $end = time() * 1000;

// connect to MySQL
$db = new PDO('OMITTED');
//checking to see if database connects
//$result = $myPDO->query("SELECT tc FROM index1");
//foreach($result as $row)
//{
//	echo $row['tc'] . "\n";
//}

// set UTC time
//mysql_query("SET time_zone = '+00:00'");
$end = $end / 1000;
$start = $start / 1000;
// set some utility variables
$range = $end - $start;
$startTime = gmstrftime('%Y-%m-%d %H:%M:%S', $start);
$endTime = gmstrftime('%Y-%m-%d %H:%M:%S', $end);

$nth = 100;

$year = 3600 * 24 * 365;
$month = 3600 * 24 * 30;
$week = 3600 * 24 * 7;
$day = 3600 * 24;

if($range < $year){
	$nth = 50;
}
if($range < $month){
	$nth = 25;
}
if($range <= $week){
	$nth = 2;
}
if($range <= $day + 3600){
	$nth = 1;
}

$table = 'index2';

//echo 'nth is ' . $nth . ' range is ' . $range;

$sql = "
	SELECT
	epoch,
	tc,
	ica,
	btc,
	eth
	FROM $table
	WHERE
	(id % $nth = 0) OR id = (SELECT MAX(id) from $table) OR id < 1348
";

//debug
//echo 'sql string: ' . $sql . '<br><br>';

$result = $db->query($sql);

$rows = array();

/*foreach($result as $row)
{
//	echo $row['date'] . ' - ' . $row['time'] . ' &nbsp&nbsp ' . $row['tc'] . ' &nbsp&nbsp ' . $row['ica'] . ' &nbsp&nbsp ' . $row['btc'] . ' &nbsp&nbsp ' .  $row['eth'] . ' ' . '<br>';
$date  = $row['date'] . ' ' . $row['time'];
//echo 'date to convert: ' . $date . '<br>';
$epochdt = new DateTime($date);
//echo $epochdt->getTimestamp() . '<br>';
$epochdt = $epochdt * 1000;

$newRow = array();
array_push($epochdt, $row['tc'], $row['ica'], $row['btc'], $row['eth']);
array_push($rows, $newRow);
}*/

#$tcRows = array();
//$icaRows = array();
//$btcRows = array();
//$ethRows = array();
foreach($result as $row) {
	extract($row);

	//change date and time to epoch date
	//$epochdt = new DateTime($date . ' ' . $time);
	//$epochdt->getTimestamp();
	//$epochdt = $epochdt * 1000;
	//$epochdt = strtotime($date . ' ' . $time);
	//$epochdt = $epochdt * 1000;
	$epoch = $epoch * 1000;
	$rows[] = "[$epoch,$tc,$ica,$btc, $eth]";
//	$tcRows[] =  "[$epochdt,$tc]";
//	$icaRows[] =  "[$epochdt,$ica]";
//	$btcRows[] =  "[$epochdt,$btc]";
//	$ethRows[] =  "[$epochdt,$eth]";
}

$numResults = count($rows);

// print it
header('Content-Type: text/javascript');

echo "/* console.log(' start = $start, end = $end, startTime = $startTime, endTime = $endTime, range = $range, nth = $nth, results = $numResults '); */";
//echo $callback ."([\n" . join(",\n", $tcRows) ."\n],[\n" . join(",\n", $icaRows) ."\n],[\n" . join(",\n", $btcRows) ."\n],[\n" . join(",\n", $ethRows) ."\n]);";
echo $callback ."([\n" . join(",\n", $rows) ."\n]);";
//echo json_encode($rows);
?>


<?php
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(E_ALL & ~E_NOTICE);
?>