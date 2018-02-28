<?php

namespace PhotoGallery\DB;

use WindowsAzure\Common\ServicesBuilder;
use MicrosoftAzure\Storage\Common\ServiceException;
use MicrosoftAzure\Storage\Table\Models\Entity;
use MicrosoftAzure\Storage\Table\Models\EdmType;

class Meta {
    
    function _constructor()
    {

    }

    function params()
    {
        $params = func_get_args();
        $this->tableName = $params[0]["tableName"];
        $this->ConnectionString_Primary = $params[0]["AzureStorageConnectionString_Primary"];
        $this->ConnectionString_Secondary = $params[0]["AzureStorageConnectionString_Secondary"];
    }

    function createTableService()
    {
        // Create table REST proxy.
        return ServicesBuilder::getInstance()->createTableService( $this->ConnectionString_Primary );
    }
    
    function CreateAzureTable()
    {

        $tableRestProxy = $this->createTableService();

        try {
            // Create table.
            $ret = $tableRestProxy->createTable( $this->tableName );
            print_r($ret);
        }
        catch(ServiceException $e){
            $code = $e->getCode();
            $error_message = $e->getMessage();
            // Handle exception based on error codes and messages.
            // Error codes and messages can be found here:
            // http://msdn.microsoft.com/library/azure/dd179438.aspx
        }

    }

    function InsertMetadata( $partitionKey, $rowKey, $properties )
    {

        $tableRestProxy = $this->createTableService();
        
        $entity = new Entity();
        $entity->setPartitionKey( $partitionKey );
        $entity->setRowKey( $rowKey );

        $entity->addProperty("Filename", EdmType::STRING, $properties["basic"]["filename"]);
        $entity->addProperty("GeometryWidth", EdmType::INT32, $properties["basic"]["geometry"]["width"]);
        $entity->addProperty("GeometryHeight", EdmType::INT32, $properties["basic"]["geometry"]["height"]);
        $entity->addProperty("Mime", EdmType::STRING, $properties["basic"]["mime"]);
        if(isset( $properties["xmp"]))
        {
            if(isset($properties["xmp"]["faces"]))
            {
                $entity->addProperty("Faces", EdmType::STRING, json_encode($properties["xmp"]["faces"]));
            }
            if(isset($properties["xmp"]["tags"]))
            {
                $entity->addProperty("Tags", EdmType::STRING, json_encode($properties["xmp"]["tags"]));
            }
            if(isset($properties["xmp"]["title"]))
            {
                $entity->addProperty("Title", EdmType::STRING, $properties["xmp"]["title"]);
            }
            if(isset($properties["xmp"]["location"]))
            {
                if(isset($properties["xmp"]["CountryName"]))
                {
                    $entity->addProperty("LocationCountry", EdmType::STRING, $properties["xmp"]["location"]["CountryName"]);
                }
                if(isset($properties["xmp"]["ProvinceState"]))
                {
                    $entity->addProperty("LocationState", EdmType::STRING, $properties["xmp"]["location"]["ProvinceState"]);
                }
                if(isset($properties["xmp"]["City"]))
                {
                    $entity->addProperty("LocationCity", EdmType::STRING, $properties["xmp"]["location"]["City"]);
                }
            }
        }

        if(isset( $properties["exif"]))
        {
            $entity->addProperty("Exif_ApertureValue", EdmType::STRING, $properties["exif"]["ApertureValue"]);
            if(isset($properties["exif"]["ColorSpace"]))
            {
                $entity->addProperty("Exif_ColorSpace", EdmType::INT32, $properties["exif"]["ColorSpace"]);
            }
            $entity->addProperty("Exif_DateTimeDigitized", EdmType::STRING, $properties["exif"]["DateTimeDigitized"]);
            $entity->addProperty("Exif_DateTimeOriginal", EdmType::STRING, $properties["exif"]["DateTimeOriginal"]);
            if(isset($properties["exif"]["DigitalZoomRatio"]))
            {
                $entity->addProperty("Exif_DigitalZoomRatio", EdmType::STRING, $properties["exif"]["DigitalZoomRatio"]);
            }
    #        $entity->addProperty("Exif_ExifImageLength", EdmType::STRING, $properties["exif"]["ExifImageLength"]);
    #        $entity->addProperty("Exif_ExifImageWidth", EdmType::STRING, $properties["exif"]["ExifImageWidth"]);
            $entity->addProperty("Exif_ExifVersion", EdmType::STRING, $properties["exif"]["ExifVersion"]);
            if(isset($properties["exif"]["Exif_IFD_Pointer"]))
            {
                $entity->addProperty("Exif_Exif_IFD_Pointer", EdmType::INT32, $properties["exif"]["Exif_IFD_Pointer"]);
            }
            $entity->addProperty("Exif_ExposureBiasValue", EdmType::STRING, $properties["exif"]["ExposureBiasValue"]);
            if(isset($properties["exif"]["ExposureMode"]))
            {
                $entity->addProperty("Exif_ExposureMode", EdmType::INT32, $properties["exif"]["ExposureMode"]);
            }
            $entity->addProperty("Exif_ExposureTime", EdmType::STRING, $properties["exif"]["ExposureTime"]);
            $entity->addProperty("Exif_FNumber", EdmType::STRING, $properties["exif"]["FNumber"]);
            $entity->addProperty("Exif_FileDateTime", EdmType::INT32, $properties["exif"]["FileDateTime"]);
            $entity->addProperty("Exif_FileName", EdmType::STRING, $properties["exif"]["FileName"]);
            $entity->addProperty("Exif_FileSize", EdmType::INT32, $properties["exif"]["FileSize"]);
            $entity->addProperty("Exif_FileType", EdmType::INT32, $properties["exif"]["FileType"]);
            $entity->addProperty("Exif_Flash", EdmType::INT32, $properties["exif"]["Flash"]);
            if(isset($properties["exif"]["FlashPixVersion"]))
            {
                $entity->addProperty("Exif_FlashPixVersion", EdmType::STRING, $properties["exif"]["FlashPixVersion"]);
            }
            $entity->addProperty("Exif_FocalLength", EdmType::STRING, $properties["exif"]["FocalLength"]);
            if(isset($properties["exif"]["FocalLengthIn35mmFilm"]))
            {
                $entity->addProperty("Exif_FocalLengthIn35mmFilm", EdmType::INT32, $properties["exif"]["FocalLengthIn35mmFilm"]);
            }
            if(isset($properties["exif"]["GPS_IFD_Pointer"]))
            {
                $entity->addProperty("Exif_GPS_IFD_Pointer", EdmType::INT32, $properties["exif"]["GPS_IFD_Pointer"]);
            }
            $entity->addProperty("Exif_ISOSpeedRatings", EdmType::INT32, $properties["exif"]["ISOSpeedRatings"]);
            if(isset($properties["exif"]["ImageDescription"]))
            {
                $entity->addProperty("Exif_ImageDescription", EdmType::STRING, $properties["exif"]["ImageDescription"]);
            }
            #$entity->addProperty("Exif_Keywords", EdmType::STRING, $properties["exif"]["Keywords"]);
            if(isset($properties["exif"]["LightSource"]))
            {
                $entity->addProperty("Exif_LightSource", EdmType::INT32, $properties["exif"]["LightSource"]);
            }
            $entity->addProperty("Exif_Make", EdmType::STRING, $properties["exif"]["Make"]);
            $entity->addProperty("Exif_MeteringMode", EdmType::INT32, $properties["exif"]["MeteringMode"]);
            $entity->addProperty("Exif_MimeType", EdmType::STRING, $properties["exif"]["MimeType"]);
            $entity->addProperty("Exif_Model", EdmType::STRING, $properties["exif"]["Model"]);
            $entity->addProperty("Exif_Orientation", EdmType::INT32, $properties["exif"]["Orientation"]);
            $entity->addProperty("Exif_ResolutionUnit", EdmType::INT32, $properties["exif"]["ResolutionUnit"]);
            if(isset($properties["exif"]["SceneCaptureType"]))
            {
                $entity->addProperty("Exif_SceneCaptureType", EdmType::INT32, $properties["exif"]["SceneCaptureType"]);
            }
            $entity->addProperty("Exif_SectionsFound", EdmType::STRING, $properties["exif"]["SectionsFound"]);
            $entity->addProperty("Exif_ShutterSpeedValue", EdmType::STRING, $properties["exif"]["ShutterSpeedValue"]);
            if(isset($properties["exif"]["Software"]))
            {
                $entity->addProperty("Exif_Software", EdmType::STRING, $properties["exif"]["Software"]);
            }
            if(isset($properties["exif"]["SubSecTimeDigitized"]))
            {
                $entity->addProperty("Exif_SubSecTimeDigitized", EdmType::STRING, $properties["exif"]["SubSecTimeDigitized"]);
            }
            if(isset($properties["exif"]["SubSecTimeOriginal"]))
            {
                $entity->addProperty("Exif_SubSecTimeOriginal", EdmType::STRING, $properties["exif"]["SubSecTimeOriginal"]);
            }
            #$entity->addProperty("Exif_Title", EdmType::STRING, $properties["exif"]["Title"]);
            if(isset($properties["exif"]["WhiteBalance"]))
            {
                $entity->addProperty("Exif_WhiteBalance", EdmType::INT32, $properties["exif"]["WhiteBalance"]);
            }
            $entity->addProperty("Exif_XResolution", EdmType::STRING, $properties["exif"]["XResolution"]);
            $entity->addProperty("Exif_YCbCrPositioning", EdmType::INT32, $properties["exif"]["YCbCrPositioning"]);
            $entity->addProperty("Exif_YResolution", EdmType::STRING, $properties["exif"]["YResolution"]);
        }

        
        try    {
            // Calling insertOrReplaceEntity, instead of insertOrMergeEntity as shown,
            // would simply replace the entity with PartitionKey "tasksSeattle" and RowKey "1".
            $tableRestProxy->insertOrMergeEntity( $this->tableName, $entity);
        }
        catch(ServiceException $e){
            // Handle exception based on error codes and messages.
            // Error codes and messages are here:
            // http://msdn.microsoft.com/library/azure/dd179438.aspx
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
        }
    }

}

?>