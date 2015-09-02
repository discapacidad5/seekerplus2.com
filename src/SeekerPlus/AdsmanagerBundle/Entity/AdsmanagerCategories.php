<?php

namespace SeekerPlus\AdsmanagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class AdsmanagerCategories
{

    private $id;

    private $parent;

    private $name;

    private $description;

    private $metadataDescription;

    private $metadataKeywords;

    private $ordering;

    private $published;

    private $limitads;

    private $usergroupsread;

    private $usergroupswrite;
    
    private $adid;
    
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getParent() {
		return $this->parent;
	}
	public function setParent($parent) {
		$this->parent = $parent;
		return $this;
	}
	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
		return $this;
	}



    /**
     * Set description
     *
     * @param string $description
     * @return AdsmanagerCategories
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set metadataDescription
     *
     * @param string $metadataDescription
     * @return AdsmanagerCategories
     */
    public function setMetadataDescription($metadataDescription)
    {
        $this->metadataDescription = $metadataDescription;

        return $this;
    }

    /**
     * Get metadataDescription
     *
     * @return string 
     */
    public function getMetadataDescription()
    {
        return $this->metadataDescription;
    }

    /**
     * Set metadataKeywords
     *
     * @param string $metadataKeywords
     * @return AdsmanagerCategories
     */
    public function setMetadataKeywords($metadataKeywords)
    {
        $this->metadataKeywords = $metadataKeywords;

        return $this;
    }

    /**
     * Get metadataKeywords
     *
     * @return string 
     */
    public function getMetadataKeywords()
    {
        return $this->metadataKeywords;
    }

    /**
     * Set ordering
     *
     * @param integer $ordering
     * @return AdsmanagerCategories
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering
     *
     * @return integer 
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set published
     *
     * @param boolean $published
     * @return AdsmanagerCategories
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set limitads
     *
     * @param integer $limitads
     * @return AdsmanagerCategories
     */
    public function setLimitads($limitads)
    {
        $this->limitads = $limitads;

        return $this;
    }

    /**
     * Get limitads
     *
     * @return integer 
     */
    public function getLimitads()
    {
        return $this->limitads;
    }

    /**
     * Set usergroupsread
     *
     * @param string $usergroupsread
     * @return AdsmanagerCategories
     */
    public function setUsergroupsread($usergroupsread)
    {
        $this->usergroupsread = $usergroupsread;

        return $this;
    }

    /**
     * Get usergroupsread
     *
     * @return string 
     */
    public function getUsergroupsread()
    {
        return $this->usergroupsread;
    }

    /**
     * Set usergroupswrite
     *
     * @param string $usergroupswrite
     * @return AdsmanagerCategories
     */
    public function setUsergroupswrite($usergroupswrite)
    {
        $this->usergroupswrite = $usergroupswrite;

        return $this;
    }

    /**
     * Get usergroupswrite
     *
     * @return string 
     */
    public function getUsergroupswrite()
    {
        return $this->usergroupswrite;
    }
	public function getAdid() {
		return $this->adid;
	}
	public function setAdid($adid) {
		$this->adid = $adid;
		return $this;
	}
	
	public function __toString(){

		return $this->getName();
	}
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->adid = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add adid
     *
     * @param \SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerAds $adid
     * @return AdsmanagerCategories
     */
    public function addAdid(\SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerAds $adid)
    {
        $this->adid[] = $adid;

        return $this;
    }

    /**
     * Remove adid
     *
     * @param \SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerAds $adid
     */
    public function removeAdid(\SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerAds $adid)
    {
        $this->adid->removeElement($adid);
    }
}
