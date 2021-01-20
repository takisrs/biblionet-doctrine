<?php
namespace Biblionet\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="contributor_types")
 */
class ContributorType{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="BookContributorAssociation", mappedBy="contributor_type")
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
     * Set name.
     *
     * @param string $name
     *
     * @return ContributorType
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
     * @return ContributorType
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
