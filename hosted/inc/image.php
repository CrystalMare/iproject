<?php

require '../config.php';
openDB();
require 'ImageProvider.php';

if (!isset($_GET['auction']) || !isset($_GET['id']))
{
    header("HTTP/1.1 404 Not Found", 404);
    echo 'Image not found0';
    exit();
}
$auctionid = $_GET['auction'];
$imageid = $_GET['id'];

$images = ImageProvider::getImagesForAuction($auctionid);
if ($images == null)
{
    header("HTTP/1.1 404 Not Found", 404);
    echo 'Image not found1';
    exit();
}

$image = $images->getImage($imageid);
if ($image == null)
{
    header("HTTP/1.1 404 Not Found", 404);
    echo 'Image not found2';
    exit();
}

//header('Content-Type: image/jpg');
echo $image;
file_get_contents($image);

closeDB();