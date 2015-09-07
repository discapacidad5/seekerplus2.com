<?php

namespace SeekerPlus\AdsmanagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;
use \DateInterval;
class AdsmanagerAds
{

    private $id;

    private $category = '0';

    private $userid;

    private $images;

    private $dateCreated;

    private $dateModified;

    private $dateRecall;

    private $expirationDate;

    private $publicationDate;

    private $recallMailSent = '0';

    private $views = '0';

    private $published = '0';

    private $metadataDescription;

    private $metadataKeywords;

    private $adLocation;

    private $adHeadline;

    private $adKeywords;

    private $adText;

    private $adPhone;

    private $adAddress;

    private $adAttentionHoursInit;
    
    private $adAttentionHoursFinish;

    private $adAttentionDaysInit;
    
    private $adAttentionDaysFinish;

    private $adLatitude;

    private $adLongitude;
    
    private $catid;
    
    private $rated=0;
    
    private $products=array();
    
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getCategory() {
		return $this->category;
	}
	public function setCategory($category) {
		$this->category = $category;
		return $this;
	}
	public function getUserid() {
		return $this->userid;
	}
	public function setUserid($userid) {
		$this->userid = $userid;
		return $this;
	}
	public function getImages() {
		return $this->images;
	}
	public function setImages($images) {
		$this->images = $images;
		return $this;
	}
	public function getDateCreated() {
		return $this->dateCreated;
	}
	public function setDateCreated($dateCreated) {
		$this->dateCreated = $dateCreated;
		return $this;
	}
	public function getDateModified() {
		return $this->dateModified;
	}
	public function setDateModified($dateModified) {
		$this->dateModified = $dateModified;
		return $this;
	}
	public function getDateRecall() {
		return $this->dateRecall;
	}
	public function setDateRecall($dateRecall) {
		$this->dateRecall = $dateRecall;
		return $this;
	}
	public function getExpirationDate() {
		return $this->expirationDate;
	}
	public function setExpirationDate($expirationDate) {
		$this->expirationDate = $expirationDate;
		return $this;
	}
	public function getPublicationDate() {
		return $this->publicationDate;
	}
	public function setPublicationDate($publicationDate) {
		$this->publicationDate = $publicationDate;
		return $this;
	}
	public function getRecallMailSent() {
		return $this->recallMailSent;
	}
	public function setRecallMailSent($recallMailSent) {
		$this->recallMailSent = $recallMailSent;
		return $this;
	}
	public function getViews() {
		return $this->views;
	}
	public function setViews($views) {
		$this->views = $views;
		return $this;
	}
	public function getPublished() {
		return $this->published;
	}
	public function setPublished($published) {
		$this->published = $published;
		return $this;
	}
	public function getMetadataDescription() {
		return $this->metadataDescription;
	}
	public function setMetadataDescription($metadataDescription) {
		$this->metadataDescription = $metadataDescription;
		return $this;
	}
	public function getMetadataKeywords() {
		return $this->metadataKeywords;
	}
	public function setMetadataKeywords($metadataKeywords) {
		$this->metadataKeywords = $metadataKeywords;
		return $this;
	}
	public function getAdLocation() {
		return $this->adLocation;
	}
	public function setAdLocation($adLocation) {
		$this->adLocation = $adLocation;
		
	}
	public function getAdHeadline() {
		return $this->adHeadline;
	}
	public function setAdHeadline($adHeadline) {
		$this->adHeadline = $adHeadline;
		return $this;
	}
	public function getAdKeywords() {
		return $this->adKeywords;
	}
	public function setAdKeywords($adKeywords) {
		$this->adKeywords = $adKeywords;
		return $this;
	}
	public function getAdText() {
		return $this->adText;
	}
	public function setAdText($adText) {
		$this->adText = $adText;
		return $this;
	}
	public function getAdPhone() {
		return $this->adPhone;
	}
	public function setAdPhone($adPhone) {
		$this->adPhone = $adPhone;
		return $this;
	}
	public function getAdAddress() {
		return $this->adAddress;
	}
	public function setAdAddress($adAddress) {
		$this->adAddress = $adAddress;
		return $this;
	}
	public function getAdAttentionHoursInit() {
		return $this->adAttentionHoursInit;
	}
	public function setAdAttentionHoursInit($adAttentionHoursInit) {
		$this->adAttentionHoursInit = $adAttentionHoursInit;
		return $this;
	}
	public function getAdAttentionHoursFinish() {
		return $this->adAttentionHoursFinish;
	}
	public function setAdAttentionHoursFinish($adAttentionHoursFinish) {
		$this->adAttentionHoursFinish = $adAttentionHoursFinish;
		return $this;
	}
	public function getAdAttentionDaysInit() {
		return $this->adAttentionDaysInit;
	}
	public function setAdAttentionDaysInit($adAttentionDaysInit) {
		$this->adAttentionDaysInit = $adAttentionDaysInit;
		return $this;
	}
	public function getAdAttentionDaysFinish() {
		return $this->adAttentionDaysFinish;
	}
	public function setAdAttentionDaysFinish($adAttentionDaysFinish) {
		$this->adAttentionDaysFinish = $adAttentionDaysFinish;
		return $this;
	}
	public function getAdLatitude() {
		return $this->adLatitude;
	}
	public function setAdLatitude($adLatitude) {
		$this->adLatitude = $adLatitude;
		return $this;
	}
	public function getAdLongitude() {
		return $this->adLongitude;
	}
	public function setAdLongitude($adLongitude) {
		$this->adLongitude = $adLongitude;
		return $this;
	}
	public function getCatid() {
		return $this->catid;
	}
	public function setCatid($catid) {
		$this->catid = $catid;
		return $this;
	}
	
	public function getProducts() {
		return $this->products;
	}
	public function setProducts($product) {
		array_push($this->products,$product);
		return $this;
	}

	public function getRated() {
		return $this->rated;
	}
	public function setRated($rated) {
		$this->rated = $rated;
		return $this;
	}
	
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->catid = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add catid
     *
     * @param \SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerCategories $catid
     * @return AdsmanagerAds
     */
    public function addCatid(\SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerCategories $catid)
    {
        $this->catid[] = $catid;

        return $this;
    }

    /**
     * Remove catid
     *
     * @param \SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerCategories $catid
     */
    public function removeCatid(\SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerCategories $catid)
    {
        $this->catid->removeElement($catid);
    }
	
	
}
