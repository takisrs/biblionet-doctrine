<?php
namespace Biblionet\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="book_contributor")
 */
class BookContributorAssociation{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Book", inversedBy="book_contributor_associations")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id")
     *
     */
    protected $book;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Contributor", inversedBy="book_contributor_associations")
     * @ORM\JoinColumn(name="contributor_id", referencedColumnName="id")
     *
     */
    protected $contributor;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ContributorType", inversedBy="book_contributor_associations")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     *
     */
    protected $contributor_type;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Book", mappedBy="category")
     */
    protected $books;

    public function __construct() {
        $this->books = new ArrayCollection();
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
     * Set name.
     *
     * @param string $name
     *
     * @return BookContributorAssociation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set book.
     *
     * @param \Biblionet\Entities\Book|null $book
     *
     * @return BookContributorAssociation
     */
    public function setBook(\Biblionet\Entities\Book $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book.
     *
     * @return \Biblionet\Entities\Book|null
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Set contributor.
     *
     * @param \Biblionet\Entities\Contributor|null $contributor
     *
     * @return BookContributorAssociation
     */
    public function setContributor(\Biblionet\Entities\Contributor $contributor = null)
    {
        $this->contributor = $contributor;

        return $this;
    }

    /**
     * Get contributor.
     *
     * @return \Biblionet\Entities\Contributor|null
     */
    public function getContributor()
    {
        return $this->contributor;
    }

    /**
     * Set contributorType.
     *
     * @param \Biblionet\Entities\ContributorType|null $contributorType
     *
     * @return BookContributorAssociation
     */
    public function setContributorType(\Biblionet\Entities\ContributorType $contributorType = null)
    {
        $this->contributor_type = $contributorType;

        return $this;
    }

    /**
     * Get contributorType.
     *
     * @return \Biblionet\Entities\ContributorType|null
     */
    public function getContributorType()
    {
        return $this->contributor_type;
    }

    /**
     * Add book.
     *
     * @param \Biblionet\Entities\Book $book
     *
     * @return BookContributorAssociation
     */
    public function addBook(\Biblionet\Entities\Book $book)
    {
        $this->books[] = $book;

        return $this;
    }

    /**
     * Remove book.
     *
     * @param \Biblionet\Entities\Book $book
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBook(\Biblionet\Entities\Book $book)
    {
        return $this->books->removeElement($book);
    }

    /**
     * Get books.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBooks()
    {
        return $this->books;
    }
}
