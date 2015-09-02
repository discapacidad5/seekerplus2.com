<?php

namespace SeekerPlus\AdsmanagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdsmanagerProduct
 */
class AdsmanagerProduct
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
    private $description;

    /**
     * @var string
     */
    private $price;

    /**
     * @var string
     */
    private $images;


    private $idAd;


    /**
     * Set name
     *
     * @param string $name
     * @return AdsmanagerProduct
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
     * Set description
     *
     * @param string $description
     * @return AdsmanagerProduct
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
     * Set price
     *
     * @param string $price
     * @return AdsmanagerProduct
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return AdsmanagerProduct
     */
    public function setImages($image)
    {
        $this->images = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImages()
    {
        return $this->images;
    }

    public function setIdAd($idAd)
    {
        $this->idAd = $idAd;

        return $this;
    }

    public function getIdAd()
    {
        return $this->idAd;
    }
}
