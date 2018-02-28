<?php

namespace PhotoGallery\Files;

use PhotoGallery\XML;

class FileReader {

    function _constructor()
    {

    }

    /*
     * Read Basic Data
     */
    function read_basic( $file )
    {

        $return = array();

        // Get standard image parameters
        $size = GetImageSize( $file, $info );
        $return["geometryWidth"] = $size[0];
        $return["geometryHeight"] = $size[1];
        $return["mime"] = $size["mime"];
        $return["filename"] = basename($file);

        return $return;
    }

    /*
     * Read XMP Data
     */
    function read_xmp( $file )
    {

        $return = array();

        // Read XMP from file using ImageMagick
        exec ("/usr/bin/convert -ping $file xmp:-", $xmlstring);

        if($xmlstring)
        {
            // Clanup XML
            $xmlstring =  join($xmlstring,"\n");
            $xmlstring = preg_replace('~(</?|\s|\-)([a-z0-9_]+):~is', '$1$2_', $xmlstring);
            $xmlstring = preg_replace('(\>\<)', '>'.chr(13).'<', $xmlstring);

            // Decode XML using SimpleXML
            $xml = SimpleXML_load_string( $xmlstring );
            // Search for Face Recognition
            $xpath = "rdf_RDF/rdf_Description/MP_RegionInfo/rdf_Description/MPRI_Regions/rdf_Bag/rdf_li";
            $xmpdata = $xml->xpath($xpath);
            if( is_array($xmpdata) )
            {
                $return["faces"]=array();
                foreach($xmpdata AS $rect)
                {
                    $person = array();
                    $person["rectangle"] = (string) $rect->rdf_Description->MPReg_Rectangle;
                    $person["personDisplayName"] = (string) $rect->rdf_Description->MPReg_PersonDisplayName;
                    $person["personEmailDigest"] = (string) $rect->rdf_Description->MPReg_PersonEmailDigest;
                    $return["faces"][] = $person;
                }
            }

            // Search for Tags
            $xpath = "rdf_RDF/rdf_Description/MicrosoftPhoto_LastKeywordXMP/rdf_Bag/rdf_li";
            $xmpdata = $xml->xpath($xpath);
            if( is_array($xmpdata) )
            {
                $return["tags"]=array();
                foreach($xmpdata AS $data)
                {
                    $return["tags"][] = (string) $data;
                }
            }

            // Search for Title
            $xpath = "rdf_RDF/rdf_Description/dc_title/rdf_Alt/rdf_li";
            $xmpdata = $xml->xpath($xpath);
            if( is_array($xmpdata) )
            {
                if( sizeof( $xmpdata )>0 )
                {
                    $return["title"] = (string) $xmpdata[0];
                }
            }

            // Search for Location
            $xpath = "rdf_RDF/rdf_Description/prefix0_LocationCreated/rdf_Bag/rdf_li/rdf_Description/prefix0_CountryName";
            $xmpdata = $xml->xpath($xpath);
            if( sizeof( $xmpdata )>0 )
            {
                $return["location"]["CountryName"] = (string) $xmpdata[0];
            }

            $xpath = "rdf_RDF/rdf_Description/prefix0_LocationCreated/rdf_Bag/rdf_li/rdf_Description/prefix1_ProvinceState";
            $xmpdata = $xml->xpath($xpath);
            if( sizeof( $xmpdata )>0 )
            {
                $return["location"]["ProvinceState"] = (string) $xmpdata[0];
            }

            $xpath = "rdf_RDF/rdf_Description/prefix0_LocationCreated/rdf_Bag/rdf_li/rdf_Description/prefix2_City";
            $xmpdata = $xml->xpath($xpath);
            if( sizeof( $xmpdata )>0 )
            {
                $return["location"]["City"] = (string) $xmpdata[0];
            }
        }
        else
        {
            echo "--------- no XMP<br>\n<br>\n";
        }

        return $return;
    }

    /*
     * Read EXIF Data
     */
    function read_exif( $file )
    {

        $return = array();

        // Read Exif Data
        $exif = @exif_read_data( $file );
        if($exif)
        {
            if( isset( $exif["ApertureValue"] ) )
            {
                $return["exif_ApertureValue"] = $exif["ApertureValue"];
            }
            if( isset( $exif["ColorSpace"] ) )
            {
                $return["exif_ColorSpace"] = $exif["ColorSpace"];
            }
            if( isset( $exif["DateTimeDigitized"] ) )
            {
                $return["exif_DateTimeDigitized"] = $exif["DateTimeDigitized"];
            }
            if( isset( $exif["DateTimeOriginal"] ) )
            {
                $return["exif_DateTimeOriginal"] = $exif["DateTimeOriginal"];
            }
            if( isset( $exif["DigitalZoomRatio"] ) )
            {
                $return["exif_DigitalZoomRatio"] = $exif["DigitalZoomRatio"];
            }
            if( isset( $exif["ExifVersion"] ) )
            {
                $return["exif_ExifVersion"] = $exif["ExifVersion"];
            }
            if( isset( $exif["Exif_IFD_Pointer"] ) )
            {
                $return["exif_Exif_IFD_Pointer"] = $exif["Exif_IFD_Pointer"];
            }
            if( isset( $exif["ExposureBiasValue"] ) )
            {
                $return["exif_ExposureBiasValue"] = $exif["ExposureBiasValue"];
            }
            if( isset( $exif["ExposureMode"] ) )
            {
                $return["exif_ExposureMode"] = $exif["ExposureMode"];
            }
            if( isset( $exif["ExposureTime"] ) )
            {
                $return["exif_ExposureTime"] = $exif["ExposureTime"];
            }
            if( isset( $exif["FNumber"] ) )
            {
                $return["exif_FNumber"] = $exif["FNumber"];
            }
            if( isset( $exif["FileDateTime"] ) )
            {
                $return["exif_FileDateTime"] = $exif["FileDateTime"];
            }
            if( isset( $exif["FileName"] ) )
            {
                $return["exif_FileName"] = $exif["FileName"];
            }
            if( isset( $exif["FileSize"] ) )
            {
                $return["exif_FileSize"] = $exif["FileSize"];
            }
            if( isset( $exif["FileType"] ) )
            {
                $return["exif_FileType"] = $exif["FileType"];
            }
            if( isset( $exif["Flash"] ) )
            {
                $return["exif_Flash"] = $exif["Flash"];
            }
            if( isset( $exif["FlashPixVersion"] ) )
            {
                $return["exif_FlashPixVersion"] = $exif["FlashPixVersion"];
            }
            if( isset( $exif["FocalLength"] ) )
            {
                $return["exif_FocalLength"] = $exif["FocalLength"];
            }
            if( isset( $exif["FocalLengthIn35mmFilm"] ) )
            {
                $return["exif_FocalLengthIn35mmFilm"] = $exif["FocalLengthIn35mmFilm"];
            }
            if( isset( $exif["GPS_IFD_Pointer"] ) )
            {
                $return["exif_GPS_IFD_Pointer"] = $exif["GPS_IFD_Pointer"];
            }
            if( isset( $exif["ISOSpeedRatings"] ) )
            {
                $return["exif_ISOSpeedRatings"] = $exif["ISOSpeedRatings"];
            }
            if( isset( $exif["ImageDescription"] ) )
            {
                $return["exif_ImageDescription"] = $exif["ImageDescription"];
            }
            if( isset( $exif["Keywords"] ) )
            {
                $return["exif_Keywords"] = $exif["Keywords"];
            }
            if( isset( $exif["LightSource"] ) )
            {
                $return["exif_LightSource"] = $exif["LightSource"];
            }
            if( isset( $exif["Make"] ) )
            {
                $return["exif_Make"] = $exif["Make"];
            }
            if( isset( $exif["MeteringMode"] ) )
            {
                $return["exif_MeteringMode"] = $exif["MeteringMode"];
            }
            if( isset( $exif["MimeType"] ) )
            {
                $return["exif_MimeType"] = $exif["MimeType"];
            }
            if( isset( $exif["Model"] ) )
            {
                $return["exif_Model"] = $exif["Model"];
            }
            if( isset( $exif["Orientation"] ) )
            {
                $return["exif_Orientation"] = $exif["Orientation"];
            }
            if( isset( $exif["ResolutionUnit"] ) )
            {
                $return["exif_ResolutionUnit"] = $exif["ResolutionUnit"];
            }
            if( isset( $exif["SceneCaptureType"] ) )
            {
                $return["exif_SceneCaptureType"] = $exif["SceneCaptureType"];
            }
            if( isset( $exif["SectionsFound"] ) )
            {
                $return["exif_SectionsFound"] = $exif["SectionsFound"];
            }
            if( isset( $exif["ShutterSpeedValue"] ) )
            {
                $return["exif_ShutterSpeedValue"] = $exif["ShutterSpeedValue"];
            }
            if( isset( $exif["Software"] ) )
            {
                $return["exif_Software"] = $exif["Software"];
            }
            if( isset( $exif["SubSecTimeDigitized"] ) )
            {
                $return["exif_SubSecTimeDigitized"] = $exif["SubSecTimeDigitized"];
            }
            if( isset( $exif["SubSecTimeOriginal"] ) )
            {
                $return["exif_SubSecTimeOriginal"] = $exif["SubSecTimeOriginal"];
            }
            if( isset( $exif["Title"] ) )
            {
                $return["exif_Title"] = $exif["Title"];
            }
            if( isset( $exif["WhiteBalance"] ) )
            {
                $return["exif_WhiteBalance"] = $exif["WhiteBalance"];
            }
            if( isset( $exif["XResolution"] ) )
            {
                $return["exif_XResolution"] = $exif["XResolution"];
            }
            if( isset( $exif["YCbCrPositioning"] ) )
            {
                $return["exif_YCbCrPositioning"] = $exif["YCbCrPositioning"];
            }
            if( isset( $exif["YResolution"] ) )
            {
                $return["exif_YResolution"] = $exif["YResolution"];
            }
        }
        else
        {
            echo "--------- no EXIF\n";
            $return = 0;
        }

        return $return;
    }

    /*
     * Create Hash
     */
    function create_hash( $file )
    {
        return hash_file("sha1", $file);
    }

}