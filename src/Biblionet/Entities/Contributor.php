<?php
namespace Biblionet\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="contributors")
 */
class Contributor{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="BookContributorAssociation", mappedBy="book")
     */
    protected $book_contributor_associations;

    public function __construct() {
        $this->book_contributor_associations = new ArrayCollection();
    }


    /**
     * Set id.
     *
     * @param int $id
     *
     * @return Contributor
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * @return Contributor
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
     * Add bookContributorAssociation.
     *
     * @param \Biblionet\Entities\BookContributorAssociation $bookContributorAssociation
     *
     * @return Contributor
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
