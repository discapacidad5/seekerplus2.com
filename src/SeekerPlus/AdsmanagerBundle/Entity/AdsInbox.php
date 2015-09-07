<?php

namespace SeekerPlus\AdsmanagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdsInbox
 */
class AdsInbox
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $subjectInbox;

    /**
     * @var string
     */
    private $messajeInbox;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $state2;

    /**
     * @var string
     */
    private $see1;

    /**
     * @var string
     */
    private $see2;

    /**
     * @var \DateTime
     */
    private $dateCreated;

    /**
     * @var \SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerAds
     */
    private $idAds;

    /**
     * @var \SeekerPlus\AdsmanagerBundle\Entity\AdsUsers
     */
    private $idUserOrigin;

    /**
     * @var \SeekerPlus\AdsmanagerBundle\Entity\AdsUsers
     */
    private $idUserDestination;


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
     * Set subjectInbox
     *
     * @param string $subjectInbox
     * @return AdsInbox
     */
    public function setSubjectInbox($subjectInbox)
    {
        $this->subjectInbox = $subjectInbox;

        return $this;
    }

    /**
     * Get subjectInbox
     *
     * @return string 
     */
    public function getSubjectInbox()
    {
        return $this->subjectInbox;
    }

    /**
     * Set messajeInbox
     *
     * @param string $messajeInbox
     * @return AdsInbox
     */
    public function setMessajeInbox($messajeInbox)
    {
        $this->messajeInbox = $messajeInbox;

        return $this;
    }

    /**
     * Get messajeInbox
     *
     * @return string 
     */
    public function getMessajeInbox()
    {
        return $this->messajeInbox;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return AdsInbox
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set state2
     *
     * @param string $state2
     * @return AdsInbox
     */
    public function setState2($state2)
    {
        $this->state2 = $state2;

        return $this;
    }

    /**
     * Get state2
     *
     * @return string 
     */
    public function getState2()
    {
        return $this->state2;
    }

    /**
     * Set see1
     *
     * @param string $see1
     * @return AdsInbox
     */
    public function setSee1($see1)
    {
        $this->see1 = $see1;

        return $this;
    }

    /**
     * Get see1
     *
     * @return string 
     */
    public function getSee1()
    {
        return $this->see1;
    }

    /**
     * Set see2
     *
     * @param string $see2
     * @return AdsInbox
     */
    public function setSee2($see2)
    {
        $this->see2 = $see2;

        return $this;
    }

    /**
     * Get see2
     *
     * @return string 
     */
    public function getSee2()
    {
        return $this->see2;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return AdsInbox
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set idAds
     *
     * @param \SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerAds $idAds
     * @return AdsInbox
     */
    public function setIdAds(\SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerAds $idAds = null)
    {
        $this->idAds = $idAds;

        return $this;
    }

    /**
     * Get idAds
     *
     * @return \SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerAds 
     */
    public function getIdAds()
    {
        return $this->idAds;
    }

    /**
     * Set idUserOrigin
     *
     * @param \SeekerPlus\AdsmanagerBundle\Entity\AdsUsers $idUserOrigin
     * @return AdsInbox
     */
    public function setIdUserOrigin(\SeekerPlus\AdsmanagerBundle\Entity\AdsUsers $idUserOrigin = null)
    {
        $this->idUserOrigin = $idUserOrigin;

        return $this;
    }

    /**
     * Get idUserOrigin
     *
     * @return \SeekerPlus\AdsmanagerBundle\Entity\AdsUsers 
     */
    public function getIdUserOrigin()
    {
        return $this->idUserOrigin;
    }

    /**
     * Set idUserDestination
     *
     * @param \SeekerPlus\AdsmanagerBundle\Entity\AdsUsers $idUserDestination
     * @return AdsInbox
     */
    public function setIdUserDestination(\SeekerPlus\AdsmanagerBundle\Entity\AdsUsers $idUserDestination = null)
    {
        $this->idUserDestination = $idUserDestination;

        return $this;
    }

    /**
     * Get idUserDestination
     *
     * @return \SeekerPlus\AdsmanagerBundle\Entity\AdsUsers 
     */
    public function getIdUserDestination()
    {
        return $this->idUserDestination;
    }
}
