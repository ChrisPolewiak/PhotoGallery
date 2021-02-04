<?php

require "vendor/autoload.php";
require "../config.php";

set_time_limit(3600);

if (ob_get_level() == 0) ob_start();

use codeStamp\codeStamp;
use PhotoGallery\Files\FileReader;
use PhotoGallery\DB\Meta;
use PhotoGallery\DB\Blob;
use PhotoGallery\DB\Files;

$cs = new codeStamp();

$files = new Files();
$files->params(
    array(
        "AzureStorageConnectionString_Primary"   => $AzureStorageConnectionString_Primary,
        "AzureStorageConnectionString_Secondary" => $AzureStorageConnectionString_Secondary
    )
);
$result = $files->listPath( "import", "" );
print_r( $result );
exit;


// create containers
$b = new Blob();
$b->params(
    array(
        "AzureStorageConnectionString_Primary"   => $AzureStorageConnectionString_Primary,
        "AzureStorageConnectionString_Secondary" => $AzureStorageConnectionString_Secondary
    )
);
#$b->CreateBlobContainer( "metadata" );
#$b->CreateBlobContainer( "photodb" );
#$b->CreateBlobContainer( "thumbnail" );
// FileReader

$AzureFiles = new AzureFiles( array(
    "AzureRestApiKey" => $AzureRestApiKey_Primary,
    "AzureRestApiVersion" => $AzureRestApiVersion,
    
    "AzureFilesUrlHost" => $AzureFilesUrlHost,
    "AzureFilesShareName" => $AzureFilesShareName
));



$result = $AzureFiles->listDirectoriesandFiles( "2018" );
print_r($result);

exit;

$reader = new FileReader();
$d = \dir( $FOTODB_PATH . "/2016/2016-06" );
$cs->codeStampShow( "Read Directory" );
$counter=0;
while (false !== ($entry = $d->read())) {
    if($entry != "." && $entry != "..")
    {
#        if($counter>5)
#            exit;

        echo "[$counter] = $image<br>";
        $cs->codeStampShow( "read" );
        ob_flush();
        flush();
            
        $image = $FOTODB_PATH . "/2016/2016-06/".$entry;

        $basicdata = $reader->read_basic( $image );
        
        $xmpdata = $reader->read_xmp( $image );
        $exifdata = $reader->read_exif( $image );
        $metadata = array_merge( $basicdata, $xmpdata, $exifdata);
        $filehash = $reader->create_hash( $image );
        $filedata = file_get_contents( $image );

        // Create thumbnail
        $imagick = new \Imagick();
        $imagick->readImageBlob( $filedata );
        $imagick->ResizeImage(200, 200, imagick::PREVIEW_BLUR, 1, $bestFit=1);
        $thumbnail_filedata = $imagick->getImageBlob();

        // export metadata
        $b->UploadBlob( "metadata", $filehash, json_encode($metadata), "application/json" );

        // export image source
        $b->UploadBlob( "photodb", $filehash, $filedata, $basicdata["mime"] );
        
        // export image thumbnail
        $b->UploadBlob( "thumbnail", $filehash, $thumbnail_filedata, $basicdata["mime"] );
        
        $counter++;
    }
}
$d->close();

?>