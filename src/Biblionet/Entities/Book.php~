<?php
namespace Biblionet\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Biblionet\Repositories\BookRepository")
 * @ORM\Table(name="books")
 */
class Book{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    protected $biblionet_id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $coverImage;

    /**
     * @ORM\Column(type="string")
     */
    protected $subtitle;

    /**
     * @ORM\Column(type="string")
     */
    protected $parallelTitle;

    /**
     * @ORM\Column(type="string")
     */
    protected $alternativeTitle;

    /**
     * @ORM\Column(type="string")
     */
    protected $originalTitle;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $isbn;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $firstPublishDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $currentPublishDate;

    /**
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="books")
     */
    protected $place;

    /**
     * @ORM\ManyToOne(targetEntity="Type", inversedBy="books")
     */
    protected $type;

    /**
     * @ORM\Column(type="integer")
     */
    protected $editionNo;

    /**
     * @ORM\Column(type="string")
     */
    protected $cover;

    /**
     * @ORM\Column(type="string")
     */
    protected $dimensions;

    /**
     * @ORM\Column(type="integer")
     */
    protected $pagesNo;

    /**
     * @ORM\Column(type="string")
     */
    protected $availability;

    /**
     * @ORM\Column(type="decimal", scale=4, precision=8)
     */
    protected $price;

    /**
     * @ORM\Column(type="decimal", scale=2, precision=4)
     */
    protected $vat;

    /**
     * @ORM\Column(type="decimal", scale=4, precision=8, options={"default":0})
     */
    protected $weight;

    /**
     * @ORM\Column(type="string")
     */
    protected $ageFrom;

    /**
     * @ORM\Column(type="string")
     */
    protected $ageTo;

    /**
     * @ORM\Column(type="text")
     */
    protected $summary;

    /**
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="books")
     */
    protected $language;

    /**
     * @ORM\Column(type="string")
     */
    protected $languageOriginal;

    /**
     * @ORM\Column(type="string")
     */
    protected $languageTranslatedFrom;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $series;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $seriesNo;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $subSeries;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $subSeriesNo;

    /**
     * @ORM\Column(type="string")
     */
    protected $multiVolumeTitle;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="books")
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="BookContributorAssociation", mappedBy="book")
     */
    protected $book_contributor_associations;

    
    public function __construct() {
        $this->book_contributor_associations = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set biblionetId.
     *
     * @param int $biblionetId
     *
     * @return Book
     */
    public function setBiblionetId($biblionetId)
    {
        $this->biblionet_id = $biblionetId;

        return $this;
    }

    /**
     * Get biblionetId.
     *
     * @return int
     */
    public function getBiblionetId()
    {
        return $this->biblionet_id;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Book
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set coverImage.
     *
     * @param string $coverImage
     *
     * @return Book
     */
    public function setCoverImage($coverImage)
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    /**
     * Get coverImage.
     *
     * @return string
     */
    public function getCoverImage()
    {
        return $this->coverImage;
    }

    /**
     * Set subtitle.
     *
     * @param string $subtitle
     *
     * @return Book
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle.
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set parallelTitle.
     *
     * @param string $parallelTitle
     *
     * @return Book
     */
    public function setParallelTitle($parallelTitle)
    {
        $this->parallelTitle = $parallelTitle;

        return $this;
    }

    /**
     * Get parallelTitle.
     *
     * @return string
     */
    public function getParallelTitle()
    {
        return $this->parallelTitle;
    }

    /**
     * Set alternativeTitle.
     *
     * @param string $alternativeTitle
     *
     * @return Book
     */
    public function setAlternativeTitle($alternativeTitle)
    {
        $this->alternativeTitle = $alternativeTitle;

        return $this;
    }

    /**
     * Get alternativeTitle.
     *
     * @return string
     */
    public function getAlternativeTitle()
    {
        return $this->alternativeTitle;
    }

    /**
     * Set originalTitle.
     *
     * @param string $originalTitle
     *
     * @return Book
     */
    public function setOriginalTitle($originalTitle)
    {
        $this->originalTitle = $originalTitle;

        return $this;
    }

    /**
     * Get originalTitle.
     *
     * @return string
     */
    public function getOriginalTitle()
    {
        return $this->originalTitle;
    }

    /**
     * Set isbn.
     *
     * @param string $isbn
     *
     * @return Book
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Get isbn.
     *
     * @return string
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * Set firstPublishDate.
     *
     * @param \DateTime $firstPublishDate
     *
     * @return Book
     */
    public function setFirstPublishDate($firstPublishDate)
    {
        $this->firstPublishDate = $firstPublishDate;

        return $this;
    }

    /**
     * Get firstPublishDate.
     *
     * @return \DateTime
     */
    public function getFirstPublishDate()
    {
        return $this->firstPublishDate;
    }

    /**
     * Set currentPublishDate.
     *
     * @param \DateTime $currentPublishDate
     *
     * @return Book
     */
    public function setCurrentPublishDate($currentPublishDate)
    {
        $this->currentPublishDate = $currentPublishDate;

        return $this;
    }

    /**
     * Get currentPublishDate.
     *
     * @return \DateTime
     */
    public function getCurrentPublishDate()
    {
        return $this->currentPublishDate;
    }

    /**
     * Set place.
     *
     * @param string $place
     *
     * @return Book
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place.
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Book
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set editionNo.
     *
     * @param int $editionNo
     *
     * @return Book
     */
    public function setEditionNo($editionNo)
    {
        $this->editionNo = $editionNo;

        return $this;
    }

    /**
     * Get editionNo.
     *
     * @return int
     */
    public function getEditionNo()
    {
        return $this->editionNo;
    }

    /**
     * Set cover.
     *
     * @param string $cover
     *
     * @return Book
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover.
     *
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set dimensions.
     *
     * @param string $dimensions
     *
     * @return Book
     */
    public function setDimensions($dimensions)
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    /**
     * Get dimensions.
     *
     * @return string
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

    /**
     * Set pagesNo.
     *
     * @param int $pagesNo
     *
     * @return Book
     */
    public function setPagesNo($pagesNo)
    {
        $this->pagesNo = $pagesNo;

        return $this;
    }

    /**
     * Get pagesNo.
     *
     * @return int
     */
    public function getPagesNo()
    {
        return $this->pagesNo;
    }

    /**
     * Set availability.
     *
     * @param string $availability
     *
     * @return Book
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * Get availability.
     *
     * @return string
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * Set price.
     *
     * @param string $price
     *
     * @return Book
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set vat.
     *
     * @param string $vat
     *
     * @return Book
     */
    public function setVat($vat)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Get vat.
     *
     * @return string
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * Set weight.
     *
     * @param string $weight
     *
     * @return Book
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight.
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set ageFrom.
     *
     * @param string $ageFrom
     *
     * @return Book
     */
    public function setAgeFrom($ageFrom)
    {
        $this->ageFrom = $ageFrom;

        return $this;
    }

    /**
     * Get ageFrom.
     *
     * @return string
     */
    public function getAgeFrom()
    {
        return $this->ageFrom;
    }

    /**
     * Set ageTo.
     *
     * @param string $ageTo
     *
     * @return Book
     */
    public function setAgeTo($ageTo)
    {
        $this->ageTo = $ageTo;

        return $this;
    }

    /**
     * Get ageTo.
     *
     * @return string
     */
    public function getAgeTo()
    {
        return $this->ageTo;
    }

    /**
     * Set summary.
     *
     * @param string $summary
     *
     * @return Book
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary.
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set language.
     *
     * @param string $language
     *
     * @return Book
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set languageOriginal.
     *
     * @param string $languageOriginal
     *
     * @return Book
     */
    public function setLanguageOriginal($languageOriginal)
    {
        $this->languageOriginal = $languageOriginal;

        return $this;
    }

    /**
     * Get languageOriginal.
     *
     * @return string
     */
    public function getLanguageOriginal()
    {
        return $this->languageOriginal;
    }

    /**
     * Set languageTranslatedFrom.
     *
     * @param string $languageTranslatedFrom
     *
     * @return Book
     */
    public function setLanguageTranslatedFrom($languageTranslatedFrom)
    {
        $this->languageTranslatedFrom = $languageTranslatedFrom;

        return $this;
    }

    /**
     * Get languageTranslatedFrom.
     *
     * @return string
     */
    public function getLanguageTranslatedFrom()
    {
        return $this->languageTranslatedFrom;
    }

    /**
     * Set series.
     *
     * @param string $series
     *
     * @return Book
     */
    public function setSeries($series)
    {
        $this->series = $series;

        return $this;
    }

    /**
     * Get series.
     *
     * @return string
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Set seriesNo.
     *
     * @param int $seriesNo
     *
     * @return Book
     */
    public function setSeriesNo($seriesNo)
    {
        $this->seriesNo = $seriesNo;

        return $this;
    }

    /**
     * Get seriesNo.
     *
     * @return int
     */
    public function getSeriesNo()
    {
        return $this->seriesNo;
    }

    /**
     * Set subSeries.
     *
     * @param string $subSeries
     *
     * @return Book
     */
    public function setSubSeries($subSeries)
    {
        $this->subSeries = $subSeries;

        return $this;
    }

    /**
     * Get subSeries.
     *
     * @return string
     */
    public function getSubSeries()
    {
        return $this->subSeries;
    }

    /**
     * Set subSeriesNo.
     *
     * @param int $subSeriesNo
     *
     * @return Book
     */
    public function setSubSeriesNo($subSeriesNo)
    {
        $this->subSeriesNo = $subSeriesNo;

        return $this;
    }

    /**
     * Get subSeriesNo.
     *
     * @return int
     */
    public function getSubSeriesNo()
    {
        return $this->subSeriesNo;
    }

    /**
     * Set multiVolumeTitle.
     *
     * @param string $multiVolumeTitle
     *
     * @return Book
     */
    public function setMultiVolumeTitle($multiVolumeTitle)
    {
        $this->multiVolumeTitle = $multiVolumeTitle;

        return $this;
    }

    /**
     * Get multiVolumeTitle.
     *
     * @return string
     */
    public function getMultiVolumeTitle()
    {
        return $this->multiVolumeTitle;
    }

    /**
     * Set category.
     *
     * @param string $category
     *
     * @return Book
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add bookContributorAssociation.
     *
     * @param \Biblionet\Entities\BookContributorAssociation $bookContributorAssociation
     *
     * @return Book
     */
    public function addBookContributorAssociation(\Biblionet\Entities\BookContributorAssociation $bookContributorAssociation)
    {
        $this->book_contributor_associations[] = $bookContributorAssociation;

        return $this;
    }

    /**
     * Remove bookContributorAssociation.
     *
     * @param \Biblionet\Entities\BookContributorAssociation $bookContributorAssociation
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBookContributorAssociation(\Biblionet\Entities\BookContributorAssociation $bookContributorAssociation)
    {
        return $this->book_contributor_associations->removeElement($bookContributorAssociation);
    }

    /**
     * Get bookContributorAssociations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBookContributorAssociations()
    {
        return $this->book_contributor_associations;
    }
}
