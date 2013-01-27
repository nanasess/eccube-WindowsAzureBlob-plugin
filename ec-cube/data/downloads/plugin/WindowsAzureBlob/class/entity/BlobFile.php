<?php
use WindowsAzure\Table\Models\Entity;
use WindowsAzure\Table\Models\EdmType;

class BlobFile extends Entity {

    var $file_name;
    var $real_filepath;

    public function __construct() {
  
    }

    public function getResources($flag = 'r') {
        return fopen($this->real_filepath, $flag);
    }

    public function getMtime() {
        return filemtime($this->real_filepath);
    }
}