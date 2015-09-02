<?php

namespace SeekerPlus\BannerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BannerClient
 */
class BannerClient
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
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $contact;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $extrainfo;

    /**
     * @var boolean
     */
    private $state;

    /**
     * @var integer
     */
    private $checkedOut;

    /**
     * @var \DateTime
     */
    private $checkedOutTime;

    /**
     * @var string
     */
    private $metakey;

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
     * Set name
     *
     * @param string $name
     * @return BannerClient
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
     * Set contact
     *
     * @param string $contact
     * @return BannerClient
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return BannerClient
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set extrainfo
     *
     * @param string $extrainfo
     * @return BannerClient
     */
    public function setExtrainfo($extrainfo)
    {
        $this->extrainfo = $extrainfo;

        return $this;
    }

    /**
     * Get extrainfo
     *
     * @return string 
     */
    public function getExtrainfo()
    {
        return $this->extrainfo;
    }

    /**
     * Set state
     *
     * @param boolean $state
     * @return BannerClient
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
     * Set checkedOut
     *
     * @param integer $checkedOut
     * @return BannerClient
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
     * @return BannerClient
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
     * Set metakey
     *
     * @param string $metakey
     * @return BannerClient
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
     * Set ownPrefix
     *
     * @param boolean $ownPrefix
     * @return BannerClient
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
     * @return BannerClient
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
     * @return BannerClient
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
     * @return BannerClient
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
     * @return BannerClient
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
}
