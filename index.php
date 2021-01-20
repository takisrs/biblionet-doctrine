<?php 
require_once "vendor/autoload.php";

define("APP_DIR", __DIR__);

use Biblionet\Doctrine;
use Biblionet\Fetcher\BooksFetcher;
use Biblionet\Entities\Book;

/*
$instance = new BooksFetcher("testuser", "testpsw");
$instance->fetch("2020", "11")->fetch("2021", "01")->store();
*/

$doctrine = new Doctrine;
$books = $doctrine->entityManager->getRepository(Book::class)->findLatest(20);
foreach($books as $book){
    echo $book->getTitle() . " => " . $book->getCategory()->getName() . " => " . $book->getPlace()->getName() . "<br/>";
}
?>