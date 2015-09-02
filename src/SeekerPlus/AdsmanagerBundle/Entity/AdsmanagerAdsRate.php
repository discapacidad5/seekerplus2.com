<?php

namespace SeekerPlus\AdsmanagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdsmanagerAdsRate
 */
class AdsmanagerAdsRate
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $idAds;

    /**
     * @var integer
     */
    private $idUser;

    /**
     * @var integer
     */
    private $rate;


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
     * Set idAds
     *
     * @param integer $idAds
     * @return AdsmanagerAdsRate
     */
    public function setIdAds($idAds)
    {
        $this->idAds = $idAds;

        return $this;
    }

    /**
     * Get idAds
     *
     * @return integer 
     */
    public function getIdAds()
    {
        return $this->idAds;
    }

    /**
     * Set idUser
     *
     * @param integer $idUser
     * @return AdsmanagerAdsRate
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return integer 
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set rate
     *
     * @param integer $rate
     * @return AdsmanagerAdsRate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return integer 
     */
    public function getRate()
    {
        return $this->rate;
    }
}
