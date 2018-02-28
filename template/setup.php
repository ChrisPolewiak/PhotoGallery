<?php

require_once 'vendor/autoload.php';

use PhotoGallery\Azure\AzureSearch;


if(isset($_REQUEST["task"]))
{
    switch ($_REQUEST["task"])
    {
        case "setup-azuresearch":
            $s = new AzureSearch( array(
                "AzureSearchUrlHost" => $AzureSearchUrlHost,
                "AzureSearchApiKey" => $AzureSearchApiKey_Primary,
                "AzureSearchUrlAPI" => $AzureSearchUrlAPI,
                "AzureSearchUrlIndex" => $AzureSearchUrlIndex,
            ));
            
            $searchDataSources = $s->getDataSource();
            foreach( $searchDataSources->value AS $v )
            {
                echo "Found data source: ".$v->name."<br>";
                if ($v->name == "photogallery-metadata")
                {
                    $s->deleteDataSource( "photogallery-metadata" );
                }
            }
            $result = $s->createDataSource(
                $dataSourceName = "photogallery-metadata",
                $dataSourceType = "azureblob",
                $storageConnectionString = $AzureStorageConnectrionString_Primary,
                $storageContainerName = "metadata",
                $storageQuery = ""
            );
            echo "<xmp>Create Data Source:\n\n";
            print_r($result);
            echo "</xmp>";
/*
            $indexFields = array(
                array("name" => "id", "type" => "Edm.String", "key" => true, "searchable" => false),
                array("name" => "id", "type" => "Edm.String", "key" => true, "searchable" => false),
            );
            createIndex( "photogallery-metadata", $indexFields );
*/
            /*
            $s->search(
                $searchQuery="Jakub",
                $searchFields="Faces",
                $queryType="simple",
                $searchMode="any",
                $count=true
            );
*/
            break;

    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PhotoGallery - Connection Setup</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>
<body>
    <h1>PhotoGallery - Connection Setup</h1>
    <div class="container">
        <ul>
            <li><a href="?task=setup-azuresearch">Setup Azure Search</a></li>
        </ul>
    </div>

</body>
</html>

<?php

