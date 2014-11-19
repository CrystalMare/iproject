<?php

class ImageProvider
{
    public static $datalocation = "auctions/";
    private static $table = "Files";
    
    static function getImagesForAuction($auction)
    {
        GLOBAL $DB;
        $images = array();
        $table = ImageProvider::$table;
        $sql =    "SELECT fileid "
                . "FROM $table "
                . "WHERE auctionid = ? "
                . "ORDER BY fileid ASC;";
        $stmt = sqlsrv_query($DB, $sql, array($auction));
        if (!$stmt)
        {
            echo "exit1";
            return null;
        }
        $i = 0;
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $images[$i] = $row['fileid'];
            $i++;
        }
        return new ImageSet($images, $auction);
    }

} 

class ImageSet
{
    private $images;
    private $auctionid;
    
    public function ImageSet($images, $auctionid)
    {
        $this->images = $images;
        $this->auctionid = $auctionid;
    }
    
    public function getImageCount()
    {
        return count($this->images);
    }
    
    public function getImage($i)
    {
        if ($i + 1 > $this->getImageCount()) return null;
        return ImageProvider::$datalocation . $this->auctionid . "/" . $this->images[$i];
    }
}
if (!isset($_GET['auction']) || !isset($_GET['id']))
{
    header("HTTP/1.1 404 Not Found", 404);
    echo 'Image not found';
    exit();
}
$auctionid = $_GET['auction'];
$imageid = $_GET['id'];

$provider = new ImageProvider();

$images = ImageProvider::getImagesForAuction($auctionid);
if ($images == null)
{
    header("HTTP/1.1 404 Not Found", 404);
    echo 'Image not found';   
    exit();
}

$image = $images->getImage($imageid);
if ($image == null)
{
    header("HTTP/1.1 404 Not Found", 404);
    echo 'Image not found';
    exit();
}

header('Content-Type: image/jpg');
readfile($image);

