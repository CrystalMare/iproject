<?php
require('../config.php');
require('Category.php');
openDB();
global $DB;

$cat = -1;

if (!isset($_GET['cat'])) {
    $cat = -1;
} else {
    $cat = $_GET['cat'];
}

header("Content-Type: application/json");
echo json_encode(Category::getCategory($cat));
closeDB();
exit();