<?php

namespace SeekerPlus\AdsmanagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdsComments
 */
class AdsComments
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $idUser;

    /**
     * @var string
     */
    private $idAds;

    /**
     * @var \DateTime
     */
    private $dateCreated;

    /**
     * @var string
     */
    private $commentAd;


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
     * Set idUser
     *
     * @param string $idUser
     * @return AdsComments
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return string 
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set idAds
     *
     * @param string $idAds
     * @return AdsComments
     */
    public function setIdAds($idAds)
    {
        $this->idAds = $idAds;

        return $this;
    }

    /**
     * Get idAds
     *
     * @return string 
     */
    public function getIdAds()
    {
        return $this->idAds;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return AdsComments
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
     * Set commentAd
     *
     * @param string $commentAd
     * @return AdsComments
     */
    public function setCommentAd($commentAd)
    {
        $this->commentAd = $commentAd;

        return $this;
    }

    /**
     * Get commentAd
     *
     * @return string 
     */
    public function getCommentAd()
    {
        return $this->commentAd;
    }
}
