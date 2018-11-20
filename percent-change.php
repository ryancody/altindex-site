<?php
$db = new PDO('OMITTED');
$table = 'index2';
$sql = "
	SELECT
    id,
	epoch,
	tc,
	ica,
	btc,
	eth
	FROM $table
	WHERE
	id = (SELECT MAX(id) from $table)
";

$result = $db->query($sql);

foreach($result as $row) {
	extract($row);
    $tcnow = $tc;
    $icanow = $ica;
    $btcnow = $btc;
    $ethnow = $eth;
}

$sql = "
	SELECT
    id,
	epoch,
	tc,
	ica,
	btc,
	eth
	FROM $table
	WHERE
	id = $id - 288
";

$result = $db->query($sql);

foreach($result as $row) {
	extract($row);
    $tcd = $tc;
    $icad = $ica;
    $btcd = $btc;
    $ethd = $eth;
}

$tcchange = (floatval($tcnow) - floatval($tcd)) / floatval($tcd) * 100;
$icachange = (floatval($icanow) - floatval($icad)) / floatval($icad) * 100;
$btcchange = (floatval($btcnow) - floatval($btcd)) / floatval($btcd) * 100;
$ethchange = (floatval($ethnow) - floatval($ethd)) / floatval($ethd) * 100;
?>


<?php
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(E_ALL & ~E_NOTICE);
?>