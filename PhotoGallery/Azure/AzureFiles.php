<?php

namespace PhotoGallery\Azure;

class AzureFiles
{

    var $AzureRestApiKey = '';
    var $AzureRestApiVersion = '';
    var $AzureFilesUrlHost = '';
    var $AzureFilesShareName = '';

    function __construct( $params )
    {
        $this->AzureRestApiKey = $params['AzureRestApiKey'];
        $this->AzureRestApiVersion = $params['AzureRestApiVersion'];
        $this->AzureFilesUrlHost = $params['AzureFilesUrlHost'];
        $this->AzureFilesShareName = $params['AzureFilesShareName'];
    }

    function __send( $urlPath, $data, $queryType )
    {

        $httpHeaders = array(
            'api-key: ' . $this->AzureRestApiKey,
            'x-ms-version: 2015-02-21',
        );

        switch ($queryType)
        {
            case 'POST':
                $ch = curl_init( 'https://' . $urlPath . '?api-version=' . $this->AzureRestApiVersion );
                curl_setopt($ch, CURLOPT_POST, 1 );
                $dataJson = json_encode( $data );
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson );
                $httpHeaders[] = 'Content-Type: application/json';
                $httpHeaders[] = 'Content-Length: ' . strlen($dataJson);
                break;

            case 'GET':
	            $ch = curl_init( 'https://' . $urlPath . '?restype=directory&comp=list' );
                break;

            case 'DELETE':
                $ch = curl_init( 'https://' . $urlPath . '/' . $data . '?api-version=' . $this->AzureRestApiVersion );
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
	            break;
        }

        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders );
        $result = curl_exec($ch);
        return json_decode($result);
	}

    /**
     * List Directories
     */
    function listDirectoriesandFiles( $directory='' )
    {
        return $this->__send(
			$this->AzureFilesUrlHost . '/' . $this->AzureFilesShareName . '/' . $directory,
			$data='',
			$queryType='GET'
		);
    }

}