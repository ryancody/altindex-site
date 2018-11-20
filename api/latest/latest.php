<?php
$db = new PDO('OMITTED');
$table = 'index2';
$sql = "
	SELECT
    id,
	epoch,
	tc
	FROM $table
	WHERE
	id = (SELECT MAX(id) from $table)
";

$result = $db->query($sql);

foreach($result as $row) {
	extract($row);
    $latestepoch = intval($epoch);
    $latesttc = floatval($tc);
}

$data = [$latestepoch, $latesttc];
header('Content-Type: application/json');
echo json_encode($data);
?>


<?php
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(E_ALL & ~E_NOTICE);
?>