<?php

namespace PhotoGallery\Azure;

class AzureSearch
{
    var $AzureRestApiKey = "";
    var $AzureRestApiVersion = "";
    var $AzureSearchUrlHost = "";
    var $AzureSearchUrlIndex = "";

    function __construct( $params )
    {
        $this->AzureRestApiKey = $params["AzureRestApiKey"];
        $this->AzureRestApiVersion = $params["AzureRestApiVersion"];
        $this->AzureSearchUrlHost = $params["AzureSearchUrlHost"];
        $this->AzureSearchUrlIndex = $params["AzureSearchUrlIndex"];
    }

    function __send( $urlPath, $data, $queryType )
    {

        $httpHeaders = array(
            "api-key: ".$this->AzureRestApiKey,
        );

        switch ($queryType)
        {
            case "POST":
                $ch = curl_init( "https://" . $urlPath . "?api-version=". $this->AzureRestApiVersion );
                curl_setopt($ch, CURLOPT_POST, 1 );
                $dataJson = json_encode( $data );
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson );
                $httpHeaders[] = "Content-Type: application/json";
                $httpHeaders[] = "Content-Length: ".strlen($dataJson);
                break;

            case "GET":
            echo "https://" . $urlPath . "?api-version=". $this->AzureRestApiVersion;
                $ch = curl_init( "https://" . $urlPath . "?api-version=". $this->AzureRestApiVersion );
#                curl_setopt($ch, CURLOPT_POSTFIELDS, $data );
            break;

            case "DELETE":
                $ch = curl_init( "https://" . $urlPath . "/" . $data . "?api-version=". $this->AzureRestApiVersion );
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
        }

#        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders );
        $result = curl_exec($ch);
        return json_decode($result);
    }

    /**
     * Get Azure Search Data Sources
     */
    function getDataSource()
    {
        return $this->__send( $this->AzureSearchUrlHost . "/datasources", $data="", $queryType="GET" );
    }

    /**
     * Delete Azure Search Data Sources
     */
    function deleteDataSource( $dataSourceName )
    {
        return $this->__send( $this->AzureSearchUrlHost . "/datasources/".$dataSourceName, $data="", $queryType="DELETE" );
    }

    /**
     * Create Azure Search Data Sources
     */
    function createDataSource( $dataSourceName, $dataSourceType, $storageConnectionString, $storageContainerName, $storageQuery )
    {
        $data = array(
            "name" => $dataSourceName,
            "type" => $dataSourceType,
            "credentials" => array(
                "connectionString" => $storageConnectionString
            ),
            "container" => array(
                "name" => $storageContainerName,
                "query" => $storageQuery
            )
        );

        return $this->__send( $this->AzureSearchUrlHost . "/datasources", $data, $queryType="POST" );
    }

    /**
     * Create Azure Search Index
     */
    function createIndex( $indexName, $indexFields )
    {
        $data = array(
            "name" => $indexName,
            "fields" => $indexFields
        );

        return $this->__send( $this->AzureSearchUrlHost . "/indexes", $data, $queryType="POST" );
    }

    /**
     * Azure Search Query
     */
    function search( $searchQuery, $searchFields, $queryType, $searchMode, $count )
    {
        $data = array(
            "search" => $searchQuery,
            "searchFields" => $searchFields,
            "queryType" => $queryType,
            "searchMode" => $searchMode,
            "count" => $count
        );

        return $this->__send( $this->AzureSearchUrlHost . "/indexes/" . $this->AzureSearchUrlIndex . "/docs/search", $data, $queryType="POST" );
    }
    
}
?>