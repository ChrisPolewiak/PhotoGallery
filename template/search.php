<?php

$q = $_GET["q"] ? $_GET["q"] : "";

use PhotoGallery\Azure\AzureSearch;

$search = new AzureSearch( array(
    "AzureRestApiKey" => $AzureRestApiKey_Primary,
    "AzureRestApiVersion" => $AzureRestApiVersion,
    
    "AzureSearchUrlHost" => $AzureSearchUrlHost,
    "AzureSearchUrlIndex" => $AzureSearchUrlIndex,
));
$searchResult = $search->search(
    $searchQuery=$q,
    $searchFields="Faces",
    $queryType="simple",
    $searchMode="any",
    $count=true );

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PhotoGallery - Search</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>
<body>
    <h1>PhotoGallery - Search</h1>
    <div class="container">
        <ul class="list-inline">
<?php
    foreach($searchResult->value AS $k=>$v)
    {
?>
            <li class="list-inline-item">
                <a href="https://<?=$AzureStorageAccountName?>.blob.core.windows.net/photodb/<?=$v->RowKey?>">
                    <img src="https://<?=$AzureStorageAccountName?>.blob.core.windows.net/thumbnail/<?=$v->RowKey?>">
                </a><br>
                <p><?=$v->Filename?></p>
            </li>
<?php
    }
?>
        </ul>
    </div>

</body>
</html>
