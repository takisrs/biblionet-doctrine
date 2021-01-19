<?php
namespace Biblionet\Fetcher;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

use Biblionet\Helper;
use Biblionet\Entities\Book;
use Biblionet\Entities\Place;
use Biblionet\Entities\Category;
use Biblionet\Entities\Language;
use Biblionet\Entities\Type;
use Biblionet\Entities\Contributor;
use Biblionet\Doctrine;
use Biblionet\Entities\BookContributorAssociation;

class BooksFetcher extends Base
{
    private $fetchedBooks = [];

    function __construct($username, $password)
    {
        parent::__construct($username, $password);
    }

    public function fetch($year, $month)
    {
        $page = 1;
        $results_per_page = 50;

        while (true){
            $fetchedBooks = $this->getBooks($year, $month, $results_per_page, $page);

            if ($fetchedBooks && is_array($fetchedBooks)){
                $this->fetchedBooks = array_merge($this->fetchedBooks, $fetchedBooks);
                $page++;
                continue;
            } else {
                break;
            }

        }

        return $this;        
        
    }


    public function getBooks($year, $month, $titles_per_page, $page)
    {
        try {
            $this->log(self::class, $year . " - " . $month, "from ". $titles_per_page*($page-1) . " to ". $titles_per_page*$page . "");

            $response = $this->client->request('POST', 'get_month_titles', [
                'form_params' => [
                    'username' => $this->username,
                    'password' => $this->password,
                    'year' => $year,
                    'month' => $month,
                    'titles_per_page' => $titles_per_page,
                    'page' => $page
                ]
            ]);
            //var_dump($response);

            $code = $response->getStatusCode(); // 200
            $reason = $response->getReasonPhrase(); // OK

            // Get all of the response headers.
            /*foreach ($response->getHeaders() as $name => $values) {
                echo $name . ': ' . implode(', ', $values) . "\r\n";
            }*/

            $body = $response->getBody();

            $result = $body->getContents();

            return Helper::isJson($result) ? json_decode($result)[0] : NULL;

        } catch (ClientException $e) {
            echo $e->getMessage();
        } catch (ConnectException $e) {
            echo $e->getMessage();
        }
    }

    private function _storeTable($entity, $value, $key = NULL){
        $records = array_column($this->fetchedBooks, $value, $key);
        $records = array_filter($records); //remove empty values

        if (count($records) > 0){
            $doctrine = new Doctrine();
            $entityManager = $doctrine->entityManager;

            foreach ($records as $id => $name){
                $criteria = $key ? ["id" => $id] : ["name" => $name];
                $record = $entityManager->getRepository($entity)->findOneBy($criteria);
                if (!$record){
                    $record = new $entity;
                    $record->setId($id);
                    $record->setName($name);
                    $entityManager->persist($record);
                }

            }

            $entityManager->flush();

        }

        

    }

    public function store(){

        if (count($this->fetchedBooks) > 0){

            $doctrine = new Doctrine();
            $entityManager = $doctrine->entityManager;

            //get writers
            $this->_storeTable(Contributor::class, "Writer", "WriterID");
            /*
            $writers = array_column($this->fetchedBooks, "Writer", "WriterID");
            $writers = array_filter($writers); //remove empty values

            if (count($writers) > 0){
                foreach ($writers as $id => $name){
                    
                    $contributor = $entityManager->getRepository(Contributor::class)->find($id);
                    if (!$contributor){
                        $contributor = new Contributor();
                        $contributor->setId($id);
                        $contributor->setName($name);
                        $entityManager->persist($contributor);
                    }

                }

            } 
            */
            

            // get book types
            $types = array_column($this->fetchedBooks, "TitleType");
            $types = array_filter(array_unique($types)); //keep unique and not empty values
            if (count($types) > 0){
                foreach ($types as $type){
                    
                    $book_type = $entityManager->getRepository(Type::class)->findOneBy(['name' => $type]);
                    if (!$book_type){
                        $book_type = new Type();
                        $book_type->setName($type);
                        $entityManager->persist($book_type);
                    }

                }

            } 


            // get categories
            $categories = array_column($this->fetchedBooks, "Category", "CategoryID");
            $categories = array_filter(array_unique($categories)); //keep unique and not empty values
            if (count($categories) > 0){
                foreach ($categories as $id => $name){
                    
                    $category = $entityManager->getRepository(Category::class)->find($id);
                    if (!$category){
                        $category = new Category();
                        $category->setId($id);
                        $category->setName($name);
                        $entityManager->persist($category);
                    }

                }

            } 


            // get languages
            $languages = array_column($this->fetchedBooks, "Language", "LanguageID") + array_column($this->fetchedBooks, "LanguageOriginal", "LanguageOriginalID") + array_column($this->fetchedBooks, "LanguageTranslatedFrom", "LanguageTranslatedFromID");
            $languages = array_filter(array_unique($languages)); //keep unique and not empty values
            if (count($languages) > 0){
                foreach ($languages as $id => $name){
                    
                    $language = $entityManager->getRepository(Language::class)->find($id);
                    if (!$language){
                        $language = new Language();
                        $language->setId($id);
                        $language->setName($name);
                        $entityManager->persist($language);
                    }

                }

            } 


            // get places
            $places = array_column($this->fetchedBooks, "Place", "PlaceID");
            $places = array_filter(array_unique($places)); //keep unique and not empty values
            if (count($places) > 0){
                foreach ($places as $id => $name){
                    
                    $place = $entityManager->getRepository(Place::class)->find($id);
                    if (!$place){
                        $place = new Place();
                        $place->setId($id);
                        $place->setName($name);
                        $entityManager->persist($place);
                    }

                }

            } 


            $entityManager->flush();


            
            foreach ($this->fetchedBooks as $item){
                $book = new Book();
                $book->setBiblionetId($item->TitlesID);
                $book->setTitle($item->Title);
                $book->setCoverImage(!empty($item->CoverImage) ? basename($item->CoverImage) : '');
                $book->setSubtitle($item->Subtitle);
                $book->setParallelTitle($item->ParallelTitle);
                $book->setAlternativeTitle($item->AlternativeTitle);
                $book->setOriginalTitle($item->OriginalTitle);
                $book->setIsbn(empty($item->ISBN) ? 'no-isbn-'.$item->TitlesID : $item->ISBN);
            
                if (!empty($item->FirstPublishDate) && $item->FirstPublishDate != "0000-00-00"){
                    $firstPublishDate = new \Datetime($item->FirstPublishDate);
                    $book->setFirstPublishDate($firstPublishDate);
                }
            
                if (!empty($item->CurrentPublishDate) && $item->CurrentPublishDate != "0000-00-00"){
                    $currentPublishDate = new \Datetime($item->CurrentPublishDate);
                    $book->setCurrentPublishDate($currentPublishDate); 
                }
            
                // place
                if (!empty($item->PlaceID)){
                    $place = $entityManager->getRepository(Place::class)->find($item->PlaceID);
                    if ($place){
                        $book->setPlace($place);
                    }
                }

                $book->setEditionNo($item->EditionNo);
                $book->setCover($item->Cover);
                $book->setDimensions($item->Dimensions);
                $book->setPagesNo($item->PageNo);
                $book->setAvailability($item->Availability);
                $book->setPrice(empty($item->Price) ? 0 : $item->Price);
                $book->setVat(empty($item->VAT) ? 0 : $item->VAT);
                $book->setWeight(empty($item->Weight) ? 0 : $item->Weight/1000);
                $book->setAgeFrom($item->AgeFrom);
                $book->setAgeTo($item->AgeTo);
                $book->setSummary($item->Summary);

                // language
                if (!empty($item->LanguageID)){
                    $language = $entityManager->getRepository(Language::class)->find($item->LanguageID);
                    if ($language){
                        $book->setLanguage($language);
                    }
                }

                // category
                if (!empty($item->CategoryID)){
                    $category = $entityManager->getRepository(Category::class)->find($item->CategoryID);
                    if ($category){
                        $book->setCategory($category);
                    }
                }

                $book->setLanguageOriginal($item->LanguageOriginal);
                $book->setLanguageTranslatedFrom($item->LanguageTranslatedFrom);
                $book->setSeries($item->Series);
                $book->setSeriesNo($item->SeriesNo);
                $book->setSeriesNo($item->SeriesNo);
                $book->setmultiVolumeTitle($item->MultiVolumeTitle);
            
                $entityManager->persist($book);
            }
        
            $entityManager->flush();

        } else {
            $this->log("books", "No book to store");
        }
        
        
    }
}

/*
TRUNCATE books;
TRUNCATE book_contributor;
TRUNCATE categories;
TRUNCATE contributors;
TRUNCATE contributor_types;
TRUNCATE languages;
TRUNCATE places;
TRUNCATE types;
*/

