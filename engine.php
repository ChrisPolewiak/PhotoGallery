<?php

require "config.php";
require_once 'vendor/autoload.php';

if(!isset($_SERVER["PATH_INFO"])){
	$_SERVER["PATH_INFO"] = "/index";
}
ob_start();

$urlarr = parse_url( $_SERVER["REQUEST_URI"] );
$url = preg_replace("/^(\/)/", "", $urlarr["path"]);
$page = preg_replace("/(\/)$/", "", $url);
$page = $page ? $page : "index";

switch( $page )
{
	case "indexer":
		require "template/indexer.php";
		break;

	case "search":
		require "template/search.php";
		break;

	case "setup":
		require "template/setup.php";
		break;

	default:
		require "template/search.php";
}

?>