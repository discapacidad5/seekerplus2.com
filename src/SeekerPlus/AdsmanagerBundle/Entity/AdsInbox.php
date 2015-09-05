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
     * @var integer
     */
    private $idUserOrigin;

    /**
     * @var integer
     */
    private $idUserDestination;

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
     * @var \DateTime
     */
    private $dateCreated;


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
     * Set idUserOrigin
     *
     * @param integer $idUserOrigin
     * @return AdsInbox
     */
    public function setIdUserOrigin($idUserOrigin)
    {
        $this->idUserOrigin = $idUserOrigin;

        return $this;
    }

    /**
     * Get idUserOrigin
     *
     * @return integer 
     */
    public function getIdUserOrigin()
    {
        return $this->idUserOrigin;
    }

    /**
     * Set idUserDestination
     *
     * @param integer $idUserDestination
     * @return AdsInbox
     */
    public function setIdUserDestination($idUserDestination)
    {
        $this->idUserDestination = $idUserDestination;

        return $this;
    }

    /**
     * Get idUserDestination
     *
     * @return integer 
     */
    public function getIdUserDestination()
    {
        return $this->idUserDestination;
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
}
