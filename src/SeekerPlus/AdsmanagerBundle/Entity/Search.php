<?php

namespace SeekerPlus\AdsmanagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Search
 */
class Search
{
    
    private $id;

    
    private $date_search;

   
    private $access_ip;

    
    private $search;

    
    private $result;

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
     * Set date_search
     *
     * @param string $date_search
     * @return Search
     */
    public function setDateSearch($date_search)
    {
        $this->date_search = $date_search;

        return $this;
    }

    /**
     * Get date_search
     *
     * @return string 
     */
    public function getDateSearch()
    {
        return $this->date_search;
    }

    /**
     * Set access_ip
     *
     * @param string $access_ip
     * @return Search
     */
    public function setAccessIp($access_ip)
    {
        $this->access_ip = $access_ip;

        return $this;
    }

    /**
     * Get access_ip
     *
     * @return string 
     */
    public function getAccessIP()
    {
        return $this->access_ip;
    }

    /**
     * Set search
     *
     * @param string $search
     * @return Search
     */
    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * Get search
     *
     * @return string 
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Set result
     *
     * @param string $result
     * @return Search
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return string 
     */
    public function getResult()
    {
        return $this->result;
    }
}
