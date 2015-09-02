<?php

namespace SeekerPlus\AdsmanagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;

/**
 * AdsmanagerCities
 */
class AdsmanagerCities
{
    /**
     * @var integer
     */
    private $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
    	$this->id=$id;
    }
    /**
     * @var integer
     */
    private $assetId;

    /**
     * @var integer
     */
    private $parentId;

    /**
     * @var integer
     */
    private $lft;

    /**
     * @var integer
     */
    private $rgt;

    /**
     * @var integer
     */
    private $level;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $extension;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var string
     */
    private $note;

    /**
     * @var string
     */
    private $description;

    /**
     * @var boolean
     */
    private $published;

    /**
     * @var integer
     */
    private $checkedOut;

    /**
     * @var \DateTime
     */
    private $checkedOutTime;

    /**
     * @var integer
     */
    private $access;

    /**
     * @var string
     */
    private $params;

    /**
     * @var string
     */
    private $latitude;

    /**
     * @var string
     */
    private $longitude;

    /**
     * @var string
     */
    private $metadata;

    /**
     * @var integer
     */
    private $createdUserId;

    /**
     * @var \DateTime
     */
    private $createdTime;

    /**
     * @var integer
     */
    private $modifiedUserId;

    /**
     * @var \DateTime
     */
    private $modifiedTime;

    /**
     * @var integer
     */
    private $hits;

    /**
     * @var string
     */
    private $language;


    /**
     * Set assetId
     *
     * @param integer $assetId
     * @return AdsmanagerCities
     */
    public function setAssetId($assetId)
    {
        $this->assetId = $assetId;

        return $this;
    }

    /**
     * Get assetId
     *
     * @return integer 
     */
    public function getAssetId()
    {
        return $this->assetId;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     * @return AdsmanagerCities
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer 
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     * @return AdsmanagerCities
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer 
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return AdsmanagerCities
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer 
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return AdsmanagerCities
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return AdsmanagerCities
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return AdsmanagerCities
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return AdsmanagerCities
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return AdsmanagerCities
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return AdsmanagerCities
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return AdsmanagerCities
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
     * Set published
     *
     * @param boolean $published
     * @return AdsmanagerCities
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
     * Set checkedOut
     *
     * @param integer $checkedOut
     * @return AdsmanagerCities
     */
    public function setCheckedOut($checkedOut)
    {
        $this->checkedOut = $checkedOut;

        return $this;
    }

    /**
     * Get checkedOut
     *
     * @return integer 
     */
    public function getCheckedOut()
    {
        return $this->checkedOut;
    }

    /**
     * Set checkedOutTime
     *
     * @param \DateTime $checkedOutTime
     * @return AdsmanagerCities
     */
    public function setCheckedOutTime($checkedOutTime)
    {
        $this->checkedOutTime = $checkedOutTime;

        return $this;
    }

    /**
     * Get checkedOutTime
     *
     * @return \DateTime 
     */
    public function getCheckedOutTime()
    {
        return $this->checkedOutTime;
    }

    /**
     * Set access
     *
     * @param integer $access
     * @return AdsmanagerCities
     */
    public function setAccess($access)
    {
        $this->access = $access;

        return $this;
    }

    /**
     * Get access
     *
     * @return integer 
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Set params
     *
     * @param string $params
     * @return AdsmanagerCities
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get params
     *
     * @return string 
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return AdsmanagerCities
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return AdsmanagerCities
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set metadata
     *
     * @param string $metadata
     * @return AdsmanagerCities
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Get metadata
     *
     * @return string 
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set createdUserId
     *
     * @param integer $createdUserId
     * @return AdsmanagerCities
     */
    public function setCreatedUserId($createdUserId)
    {
        $this->createdUserId = $createdUserId;

        return $this;
    }

    /**
     * Get createdUserId
     *
     * @return integer 
     */
    public function getCreatedUserId()
    {
        return $this->createdUserId;
    }

    /**
     * Set createdTime
     *
     * @param \DateTime $createdTime
     * @return AdsmanagerCities
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    /**
     * Get createdTime
     *
     * @return \DateTime 
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * Set modifiedUserId
     *
     * @param integer $modifiedUserId
     * @return AdsmanagerCities
     */
    public function setModifiedUserId($modifiedUserId)
    {
        $this->modifiedUserId = $modifiedUserId;

        return $this;
    }

    /**
     * Get modifiedUserId
     *
     * @return integer 
     */
    public function getModifiedUserId()
    {
        return $this->modifiedUserId;
    }

    /**
     * Set modifiedTime
     *
     * @param \DateTime $modifiedTime
     * @return AdsmanagerCities
     */
    public function setModifiedTime($modifiedTime)
    {
        $this->modifiedTime = $modifiedTime;

        return $this;
    }

    /**
     * Get modifiedTime
     *
     * @return \DateTime 
     */
    public function getModifiedTime()
    {
        return $this->modifiedTime;
    }

    /**
     * Set hits
     *
     * @param integer $hits
     * @return AdsmanagerCities
     */
    public function setHits($hits)
    {
        $this->hits = $hits;

        return $this;
    }

    /**
     * Get hits
     *
     * @return integer 
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return AdsmanagerCities
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
    }
    
    public function getCities(EntityManager $em){

    	$query = $em->createQuery(
    			'SELECT c
				    FROM AdsmanagerBundle:AdsmanagerCities c
				    WHERE c.published = 1
    				Order by c.alias ASC'
    	
    	);
    	
    	$cities = $query->getResult();
    	
	    $adCities=Array();
    	
    	foreach ($cities as $city){
    		if($city->getparentId()!=0)
    			$adCities += array($city->getTitle() => ucfirst($city->getAlias()));
    	}
    	return $adCities;
    }
    
    public function getAdCity(EntityManager $em,$userLocation) {
    	 
   	 
    	$city=new AdsmanagerCities();
    	 
    	$adCity=$em
    	->getRepository("AdsmanagerBundle:AdsmanagerCities")
    	->findBy(array('title' => $userLocation),
    	array('alias' => 'asc'));

    	foreach ($adCity as $cities){
    		$city->setId($cities->getId());
    		$city->setTitle($cities->getTitle());
    		$city->setAlias($cities->getAlias());
    		$city->setLatitude($cities->getLatitude());
    		$city->setLongitude($cities->getLongitude());
    	}
    	 
    	if(!$adCity){
    
    		$city->setId($GLOBALS['kernel']->getContainer()->getParameter('locationId'));
    		$city->setTitle($GLOBALS['kernel']->getContainer()->getParameter('location'));
    		$city->setAlias(strtoupper($GLOBALS['kernel']->getContainer()->getParameter('alias')));
    		$city->setLatitude($GLOBALS['kernel']->getContainer()->getParameter('latitude'));
    		$city->setLongitude($GLOBALS['kernel']->getContainer()->getParameter('longitude'));
    	}
    
    	return $city;
    }
    
    public function setAdCity(EntityManager $em,$cityId) {
    
    
    	$city=new AdsmanagerCities();
    
    	$adCity=$em
    	->getRepository("AdsmanagerBundle:AdsmanagerCities")
    	->findBy(array('id' => $cityId),
    			array('alias' => 'asc'));
    
    	foreach ($adCity as $cities){
    		$city->setId($cities->getId());
    		$city->setTitle($cities->getTitle());
    		$city->setAlias(strtoupper($cities->getAlias()));
    		$city->setLatitude($cities->getLatitude());
    		$city->setLongitude($cities->getLongitude());
    	}
    
    	if(!$adCity){
    		$city->setId($GLOBALS['kernel']->getContainer()->getParameter('locationId'));
    		$city->setTitle($GLOBALS['kernel']->getContainer()->getParameter('location'));
    		$city->setAlias(strtoupper($GLOBALS['kernel']->getContainer()->getParameter('alias')));
    		$city->setLatitude($GLOBALS['kernel']->getContainer()->getParameter('latitude'));
    		$city->setLongitude($GLOBALS['kernel']->getContainer()->getParameter('longitude'));
    	}
    
    	return $city;
    }
    
    public function getAllCities(EntityManager $em){
    	$adCities=$em
    	->getRepository("AdsmanagerBundle:AdsmanagerCities")
    	->findBy(array(),
    			array('alias' => 'asc'));
    	return $adCities;
    }
}
