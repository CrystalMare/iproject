<?php

class ImageProvider
{
    var $datalocation;
    var $table;
    
    function ImageProvider()
    {
        $this->datalocation = "auctions/img/";
        $this->table = "Files";
    }
    
    function getImagesForAuction($auction)
    {
        
    }

} 

class ImageSet
{
    private $images;
    
    function ImageSet(array $images)
    {
        $this->images = $images;
    }
    
    function getImageCount()
    {
        return count($this->images);
    }
    
    function getImage(int $i)
    {
        if ($i > getImageCount())
            return null;
        return $images[$i];
    }
}