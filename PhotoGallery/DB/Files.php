<?php

namespace PhotoGallery\DB;

use MicrosoftAzure\Storage\Common\ServicesBuilder;
use MicrosoftAzure\Storage\File\Models\ListDirectoriesAndFilesResult;
use MicrosoftAzure\Storage\Common\ServiceException;

class Files {

    function __construct()
    {

    }

    function params()
    {
        $params = func_get_args();
        $this->ConnectionString_Primary   = $params[0]['AzureStorageConnectionString_Primary'];
        $this->ConnectionString_Secondary = $params[0]['AzureStorageConnectionString_Secondary'];
    }

    function listPath( $shareName, $path )
    {
        // Create table REST proxy.
        $fileRestProxy = ServicesBuilder::getInstance()->createFileService( $this->ConnectionString_Primary );
        $result = $fileRestProxy->listDirectoriesAndFiles( $shareName, $path );
        $directories = $result->getDirectories();
        $files = $result->getFiles();


        foreach ( $directories AS $k=>$v )
        {
            print_r($v);
//            print_r($v);
        }
        print_r( $directories );
        print_r( $files );

    }

}


?>