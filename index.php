<?php 
require_once "vendor/autoload.php";

define("APP_DIR", __DIR__);

use Biblionet\Fetcher\BooksFetcher;

$instance = new BooksFetcher("testuser", "testpsw");

$instance->fetch("2020", "11")->fetch("2021", "01")->store();

?>