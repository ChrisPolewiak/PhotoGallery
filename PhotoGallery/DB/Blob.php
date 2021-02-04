<?php

namespace PhotoGallery\DB;

use MicrosoftAzure\Storage\Common\ServicesBuilder;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
use MicrosoftAzure\Storage\Common\ServiceException;


class Blob {

    function __construct()
    {

    }

    function params()
    {
        $params = func_get_args();
        $this->ConnectionString_Primary = $params[0]['AzureStorageConnectionString_Primary'];
        $this->ConnectionString_Secondary = $params[0]['AzureStorageConnectionString_Secondary'];
    }

    function createBlobService()
    {
        // Create table REST proxy.
        return ServicesBuilder::getInstance()->createBlobService( $this->ConnectionString_Primary );
    }

    function CreateBlobContainer( $containerName )
    {

        $blobRestProxy = $this->createBlobService();

        $createContainerOptions = new CreateContainerOptions();
        $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);

        try {
            $blobRestProxy->createContainer( $containerName, $createContainerOptions);
        }
        catch(ServiceException $e){
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code . ': ' . $error_message . '<br />';
        }

    }

    function UploadBlob( $containerName, $filename, $filedata, $contentType )
    {

        $blobRestProxy = $this->createBlobService();

        $options = new CreateBlobOptions();
        $options->setBlobContentType($contentType);

        try    {
            // Upload blob
            $blobRestProxy->createBlockBlob( $containerName, $filename, $filedata, $options );
        }
        catch(ServiceException $e){
            // Handle exception based on error codes and messages.
            // Error codes and messages are here:
            // http://msdn.microsoft.com/library/azure/dd179439.aspx
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code . ': ' . $error_message . '<br />';
        }
    }

}