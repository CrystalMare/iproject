<?php

require('../config.php');
openDB();

$info = array();
$info['titel'] = "HOI";
$info['veilingnummer'] = $_GET['veiling'];

header('Content-Type: application/json');
echo json_encode($info);
closeDB();
exit();