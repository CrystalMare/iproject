<?php
class ImageProvider
{
    public static $datalocation = "http://iproject5.icasites.nl/";
    private static $table = "Bestand";
    
    static function getImagesForAuction($auction)
    {
        GLOBAL $DB;
        $images = array();
        $table = ImageProvider::$table;
        $sql =    "SELECT filenaam "
                . "FROM $table "
                . "WHERE voorwerpnummer = ? "
                . "ORDER BY filenaam ASC;";
        $stmt = sqlsrv_query($DB, $sql, array($auction));
        if (!$stmt)
        {
            var_dump(sqlsrv_errors());
            return null;
        }
        $i = 0;
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $images[$i] = $row['filenaam'];
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
        return ImageProvider::$datalocation . $this->images[$i];
    }
}