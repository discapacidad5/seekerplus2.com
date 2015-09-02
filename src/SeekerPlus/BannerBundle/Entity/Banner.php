<?php

namespace SeekerPlus\BannerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Banner
 */
class Banner
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
    /**
     * @var integer
     */
    private $cid;

    /**
     * @var integer
     */
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var integer
     */
    private $imptotal;

    /**
     * @var integer
     */
    private $impmade;

    /**
     * @var integer
     */
    private $clicks;

    /**
     * @var string
     */
    private $clickurl;

    /**
     * @var boolean
     */
    private $state;

    /**
     * @var integer
     */
    private $catid;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $custombannercode;

    /**
     * @var boolean
     */
    private $sticky;

    /**
     * @var integer
     */
    private $ordering;

    /**
     * @var string
     */
    private $metakey;

    /**
     * @var string
     */
    private $params;

    /**
     * @var boolean
     */
    private $ownPrefix;

    /**
     * @var string
     */
    private $metakeyPrefix;

    /**
     * @var boolean
     */
    private $purchaseType;

    /**
     * @var boolean
     */
    private $trackClicks;

    /**
     * @var boolean
     */
    private $trackImpressions;

    /**
     * @var integer
     */
    private $checkedOut;

    /**
     * @var \DateTime
     */
    private $checkedOutTime;

    /**
     * @var \DateTime
     */
    private $publishUp;

    /**
     * @var \DateTime
     */
    private $publishDown;

    /**
     * @var \DateTime
     */
    private $reset;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var string
     */
    private $language;


    /**
     * Set cid
     *
     * @param integer $cid
     * @return Banner
     */
    public function setCid($cid)
    {
        $this->cid = $cid;

        return $this;
    }

    /**
     * Get cid
     *
     * @return integer 
     */
    public function getCid()
    {
        return $this->cid;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Banner
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Banner
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return Banner
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
     * Set imptotal
     *
     * @param integer $imptotal
     * @return Banner
     */
    public function setImptotal($imptotal)
    {
        $this->imptotal = $imptotal;

        return $this;
    }

    /**
     * Get imptotal
     *
     * @return integer 
     */
    public function getImptotal()
    {
        return $this->imptotal;
    }

    /**
     * Set impmade
     *
     * @param integer $impmade
     * @return Banner
     */
    public function setImpmade($impmade)
    {
        $this->impmade = $impmade;

        return $this;
    }

    /**
     * Get impmade
     *
     * @return integer 
     */
    public function getImpmade()
    {
        return $this->impmade;
    }

    /**
     * Set clicks
     *
     * @param integer $clicks
     * @return Banner
     */
    public function setClicks($clicks)
    {
        $this->clicks = $clicks;

        return $this;
    }

    /**
     * Get clicks
     *
     * @return integer 
     */
    public function getClicks()
    {
        return $this->clicks;
    }

    /**
     * Set clickurl
     *
     * @param string $clickurl
     * @return Banner
     */
    public function setClickurl($clickurl)
    {
        $this->clickurl = $clickurl;

        return $this;
    }

    /**
     * Get clickurl
     *
     * @return string 
     */
    public function getClickurl()
    {
        return $this->clickurl;
    }

    /**
     * Set state
     *
     * @param boolean $state
     * @return Banner
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return boolean 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set catid
     *
     * @param integer $catid
     * @return Banner
     */
    public function setCatid($catid)
    {
        $this->catid = $catid;

        return $this;
    }

    /**
     * Get catid
     *
     * @return integer 
     */
    public function getCatid()
    {
        return $this->catid;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Banner
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
     * Set custombannercode
     *
     * @param string $custombannercode
     * @return Banner
     */
    public function setCustombannercode($custombannercode)
    {
        $this->custombannercode = $custombannercode;

        return $this;
    }

    /**
     * Get custombannercode
     *
     * @return string 
     */
    public function getCustombannercode()
    {
        return $this->custombannercode;
    }

    /**
     * Set sticky
     *
     * @param boolean $sticky
     * @return Banner
     */
    public function setSticky($sticky)
    {
        $this->sticky = $sticky;

        return $this;
    }

    /**
     * Get sticky
     *
     * @return boolean 
     */
    public function getSticky()
    {
        return $this->sticky;
    }

    /**
     * Set ordering
     *
     * @param integer $ordering
     * @return Banner
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
     * Set metakey
     *
     * @param string $metakey
     * @return Banner
     */
    public function setMetakey($metakey)
    {
        $this->metakey = $metakey;

        return $this;
    }

    /**
     * Get metakey
     *
     * @return string 
     */
    public function getMetakey()
    {
        return $this->metakey;
    }

    /**
     * Set params
     *
     * @param string $params
     * @return Banner
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
     * Set ownPrefix
     *
     * @param boolean $ownPrefix
     * @return Banner
     */
    public function setOwnPrefix($ownPrefix)
    {
        $this->ownPrefix = $ownPrefix;

        return $this;
    }

    /**
     * Get ownPrefix
     *
     * @return boolean 
     */
    public function getOwnPrefix()
    {
        return $this->ownPrefix;
    }

    /**
     * Set metakeyPrefix
     *
     * @param string $metakeyPrefix
     * @return Banner
     */
    public function setMetakeyPrefix($metakeyPrefix)
    {
        $this->metakeyPrefix = $metakeyPrefix;

        return $this;
    }

    /**
     * Get metakeyPrefix
     *
     * @return string 
     */
    public function getMetakeyPrefix()
    {
        return $this->metakeyPrefix;
    }

    /**
     * Set purchaseType
     *
     * @param boolean $purchaseType
     * @return Banner
     */
    public function setPurchaseType($purchaseType)
    {
        $this->purchaseType = $purchaseType;

        return $this;
    }

    /**
     * Get purchaseType
     *
     * @return boolean 
     */
    public function getPurchaseType()
    {
        return $this->purchaseType;
    }

    /**
     * Set trackClicks
     *
     * @param boolean $trackClicks
     * @return Banner
     */
    public function setTrackClicks($trackClicks)
    {
        $this->trackClicks = $trackClicks;

        return $this;
    }

    /**
     * Get trackClicks
     *
     * @return boolean 
     */
    public function getTrackClicks()
    {
        return $this->trackClicks;
    }

    /**
     * Set trackImpressions
     *
     * @param boolean $trackImpressions
     * @return Banner
     */
    public function setTrackImpressions($trackImpressions)
    {
        $this->trackImpressions = $trackImpressions;

        return $this;
    }

    /**
     * Get trackImpressions
     *
     * @return boolean 
     */
    public function getTrackImpressions()
    {
        return $this->trackImpressions;
    }

    /**
     * Set checkedOut
     *
     * @param integer $checkedOut
     * @return Banner
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
     * @return Banner
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
     * Set publishUp
     *
     * @param \DateTime $publishUp
     * @return Banner
     */
    public function setPublishUp($publishUp)
    {
        $this->publishUp = $publishUp;

        return $this;
    }

    /**
     * Get publishUp
     *
     * @return \DateTime 
     */
    public function getPublishUp()
    {
        return $this->publishUp;
    }

    /**
     * Set publishDown
     *
     * @param \DateTime $publishDown
     * @return Banner
     */
    public function setPublishDown($publishDown)
    {
        $this->publishDown = $publishDown;

        return $this;
    }

    /**
     * Get publishDown
     *
     * @return \DateTime 
     */
    public function getPublishDown()
    {
        return $this->publishDown;
    }

    /**
     * Set reset
     *
     * @param \DateTime $reset
     * @return Banner
     */
    public function setReset($reset)
    {
        $this->reset = $reset;

        return $this;
    }

    /**
     * Get reset
     *
     * @return \DateTime 
     */
    public function getReset()
    {
        return $this->reset;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Banner
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return Banner
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
}
