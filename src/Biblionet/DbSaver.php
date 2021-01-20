<?php

namespace Biblionet;

use Biblionet\Entities\Book;
use Biblionet\Entities\Place;
use Biblionet\Entities\Category;
use Biblionet\Entities\Language;
use Biblionet\Entities\Type;
use Biblionet\Entities\Contributor;
use Biblionet\DoctrineBootstrapper;
use Biblionet\Entities\BookContributorAssociation;
use DateTime;


class DbSaver
{

    private $items = [];
    private $logger;
    private $entityManager;

    public function __construct($items)
    {
        $this->items = $items;
        $this->logger = new Logger(true);
        $doctrine = new DoctrineBootstrapper();
        $this->entityManager = $doctrine->getEntityManager();
    }


    private function _storeTable($entity, $value, $key = NULL)
    {
        $records = array_column($this->items, $value, $key);
        $records = array_filter($records); //remove empty values

        if (count($records) > 0) {


            foreach ($records as $id => $name) {
                $criteria = $key ? ["id" => $id] : ["name" => $name];
                $this->logger->log('info', self::class, $entity, 'processing ' . $id . ' - ' . $name);
                $record = $this->entityManager->getRepository($entity)->findOneBy($criteria);
                if (!$record) {
                    $record = new $entity;
                    $record->setId($id);
                    $record->setName($name);
                    $this->entityManager->persist($record);
                }
            }

            $this->entityManager->flush();
        }
    }

    public function store()
    {

        if (count($this->items) > 0) {

            //get writers
            $this->_storeTable(Contributor::class, "Writer", "WriterID");
            $this->_storeTable(Category::class, "Category", "CategoryID");
            $this->_storeTable(Place::class, "Place", "PlaceID");
            $this->_storeTable(Type::class, "TitleType");
            

            // get languages
            $languages = array_column($this->items, "Language", "LanguageID") + array_column($this->items, "LanguageOriginal", "LanguageOriginalID") + array_column($this->items, "LanguageTranslatedFrom", "LanguageTranslatedFromID");
            $languages = array_filter(array_unique($languages)); //keep unique and not empty values
            if (count($languages) > 0) {
                foreach ($languages as $id => $name) {

                    $language = $this->entityManager->getRepository(Language::class)->find($id);
                    if (!$language) {
                        $language = new Language();
                        $language->setId($id);
                        $language->setName($name);
                        $this->entityManager->persist($language);
                    }
                }
            }


            $this->entityManager->flush();



            foreach ($this->items as $item) {
                $book = $this->entityManager->getRepository(Book::class)->findOneBy(["biblionet_id" => $item->TitlesID]);

                if (!$book){
                    $book = new Book();
                    $this->logger->log('info', self::class, 'book ' . $item->TitlesID . ' will be created');
                } else {
                    $this->logger->log('info', self::class, 'book ' . $item->TitlesID . ' exists and will be updated');
                }
                
                $book->setBiblionetId($item->TitlesID);
                $book->setTitle($item->Title);
                $book->setCoverImage(!empty($item->CoverImage) ? basename($item->CoverImage) : '');
                $book->setSubtitle($item->Subtitle);
                $book->setParallelTitle($item->ParallelTitle);
                $book->setAlternativeTitle($item->AlternativeTitle);
                $book->setOriginalTitle($item->OriginalTitle);
                $book->setIsbn(empty($item->ISBN) ? 'no-isbn-' . $item->TitlesID : $item->ISBN);

                if (!empty($item->FirstPublishDate) && $item->FirstPublishDate != "0000-00-00") {
                    $firstPublishDate = new \Datetime($item->FirstPublishDate);
                    $book->setFirstPublishDate($firstPublishDate);
                }

                if (!empty($item->CurrentPublishDate) && $item->CurrentPublishDate != "0000-00-00") {
                    $currentPublishDate = new \Datetime($item->CurrentPublishDate);
                    $book->setCurrentPublishDate($currentPublishDate);
                }

                // place
                if (!empty($item->PlaceID)) {
                    $place = $this->entityManager->getRepository(Place::class)->find($item->PlaceID);
                    if ($place) {
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
                $book->setWeight(empty($item->Weight) ? 0 : $item->Weight / 1000);
                $book->setAgeFrom($item->AgeFrom);
                $book->setAgeTo($item->AgeTo);
                $book->setSummary($item->Summary);

                // language
                if (!empty($item->LanguageID)) {
                    $language = $this->entityManager->getRepository(Language::class)->find($item->LanguageID);
                    if ($language) {
                        $book->setLanguage($language);
                    }
                }

                // category
                if (!empty($item->CategoryID)) {
                    $category = $this->entityManager->getRepository(Category::class)->find($item->CategoryID);
                    if ($category) {
                        $book->setCategory($category);
                    }
                }

                $book->setLanguageOriginal($item->LanguageOriginal);
                $book->setLanguageTranslatedFrom($item->LanguageTranslatedFrom);
                $book->setSeries($item->Series);
                $book->setSeriesNo($item->SeriesNo);
                $book->setSeriesNo($item->SeriesNo);
                $book->setmultiVolumeTitle($item->MultiVolumeTitle);

                $this->entityManager->persist($book);
            }

            $this->entityManager->flush();
        } else {
            $this->logger->log("info", self::class, Book::class, "No books to store");
        }
    }

}
