<?php
require_once(realpath(dirname( __FILE__)) . '/../../define.php');
require_once(realpath(dirname( __FILE__)) . '/../entity/BlobFile.php');

use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Blob\Models\CreateContainerOptions;
use WindowsAzure\Blob\Models\PublicAccessType;
use WindowsAzure\Blob\Models\CreateBlobOptions;

class SC_Helper_AzureBlob {

    private $instance;
    protected function __construct() {
        $this->initialize();
    }

    public function getInstance() {
        if (is_null($this->instance)) {
            $this->instance = new self();
        }
        return $this->instance;
    }

    protected function initialize() {
        $this->connectionString = 'DefaultEndpointsProtocol=' . ENDPOINT_PROTOCOL . ';AccountName=' . AZURE_BLOB_ACCOUNT_NAME . ';AccountKey=' . AZURE_BLOB_ACCOUNT_KEY;
        $this->containerName = sha1(HTTP_URL);
        $this->blobRestProxy = ServicesBuilder::getInstance()->createBlobService($this->connectionString);
        $objListContainersResult = $this->blobRestProxy->listContainers();
        foreach ($objListContainersResult->getContainers() as $objContainer) {
            if ($objContainer->getName() == $this->containerName) {
                return;
            }
        }

        $createContainerOptions = new CreateContainerOptions();
        $createContainerOptions->setPublicAccess(PublicAccessType::BLOBS_ONLY);
        try {
            // Create container.
            $this->blobRestProxy->createContainer($this->containerName, $createContainerOptions);
        } catch(ServiceException $e){
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
        }
    }


    public function copyToBlob(BlobFile $objFile) {
        $objBlobMetadataResult = $this->blobRestProxy->getBlobMetadata($this->containerName, 'save_image/' . $objFile->file_name);
        $arrMetadata = $objBlobMetadataResult->getMetadata();
        if ($arrMetadata['mtime'] == $objFile->getMtime()) {
            var_dump('equals');
            return;
        } elseif ($arrMetadata['mtime'] > $objFile->getMtime()) {
            var_dump('get blob');
        } else {
            var_dump('put blob');
        }
        try {
            $createBlobOptions = new CreateBlobOptions();
            $createBlobOptions->setMetadata(array('mtime' => $objFile->getMtime()));
            $this->blobRestProxy->createBlockBlob($this->containerName, 'save_image/' . $objFile->file_name, $objFile->getResources(), $createBlobOptions);
        } catch (ServiceException $e) {
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
        }
    }
}