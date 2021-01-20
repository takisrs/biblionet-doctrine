<?php 
require_once "vendor/autoload.php";

define("APP_DIR", __DIR__);

use Biblionet\ApiFetcher;
use Biblionet\DbSaver;


$fetcher = new ApiFetcher("testuser", "testpsw");
$fetcher->fetchById(['251710', '252220'])->fill(["contributors", "subjects"])->fill("companies");

//$fetcher->fetchByRange("2020-08-01");

$dbSaver = new Dbsaver($fetcher->getFetchedItems());
$dbSaver->store();

/*
$doctrine = new Doctrine;
$books = $doctrine->entityManager->getRepository(Book::class)->findLatest(20);
foreach($books as $book){
    echo $book->getTitle() . " => " . $book->getCategory()->getName() . " => " . $book->getPlace()->getName() . "<br/>";
}
*/
?>