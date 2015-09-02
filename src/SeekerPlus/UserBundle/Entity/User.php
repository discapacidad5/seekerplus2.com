<?php

namespace SeekerPlus\UserBundle\Entity;

use FOS\UserBundle\Entity\User as FOSBaseUser;
use Doctrine\ORM\Mapping as ORM;
use \DateTime;

class User extends FOSBaseUser
{

	private $name = '';
	private $usertype = '';
	private $block = '0';
	private $sendemail = '0';
	private $registerdate;
	private $lastvisitdate;
	private $activation = '';
	private $params='{"admin_style":"","admin_language":"","language":"","editor":"","helpsite":"","timezone":""}';
	private $lastresettime;
	private $resetcount=0;
	/**
	 * @var string
	 *
	 * @ORM\Column(name="firstname", type="string", length=255)
	 */
	protected $firstname;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="lastname", type="string", length=255)
	 */
	protected $lastname;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="facebookId", type="string", length=255)
	 */
	protected $facebookId;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="birthday", type="date")
	 */
	protected $birthday;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="location", type="string", length=255)
	 */
	protected $location;
	/**
	 * @var int
	 * free=0
	 * plus=1
	 * premium=2
	 */
	private $accountType=0;
	
	public function serialize()
	{
		return serialize(array($this->facebookId, parent::serialize()));
	}

	public function unserialize($data)
	{
		list($this->facebookId, $parentData) = unserialize($data);
		parent::unserialize($parentData);
	}

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
	 * Set usertype
	 *
	 * @param string $usertype
	 * @return SeekerUsers
	 */
	public function setUsertype($usertype)
	{
		$this->usertype = $usertype;
	
		return $this;
	}
	
	/**
	 * Get usertype
	 *
	 * @return string
	 */
	public function getUsertype()
	{
		return $this->usertype;
	}
	/**
	 * Set block
	 *
	 * @param boolean $block
	 * @return SeekerUsers
	 */
	public function setBlock($block)
	{
		$this->block = $block;
	
		return $this;
	}
	/**
	 * Get block
	 *
	 * @return boolean
	 */
	public function getBlock()
	{
		return $this->block;
	}
	public function setEnabled($enabled)
	{
		$this->enabled = $enabled;
		if($enabled==0)
		$this->block = 1;
		else 
		$this->block = 0;
		return $this;
	}
	
	/**
	 * Get block
	 *
	 * @return boolean
	 */
	public function getEnabled()
	{
		return $this->$enabled;
	}
	/**
	 * Set registerdate
	 *
	 * @param \DateTime $registerdate
	 * @return SeekerUsers
	 */
	public function setRegisterdate($registerdate)
	{
		$this->registerdate = $registerdate;
	
		return $this;
	}
	
	/**
	 * Get registerdate
	 *
	 * @return \DateTime
	 */
	public function getRegisterdate()
	{
		return $this->registerdate;
	}
	
	public function setLastLogin(\DateTime $time)
	{
		$this->lastLogin = $time;
		$this->lastvisitdate = $time;
	
		return $this;
	}

	/**
	 * Set lastvisitdate
	 *
	 * @param \DateTime $lastvisitdate
	 * @return SeekerUsers
	 */
	public function setLastvisitdate($lastvisitdate)
	{
		$this->lastvisitdate = $lastvisitdate;
	
		return $this;
	}
	
	/**
	 * Get lastvisitdate
	 *
	 * @return \DateTime
	 */
	public function getLastvisitdate()
	{
		return $this->lastvisitdate;
	}
	/**
	 * Set activation
	 *
	 * @param string $activation
	 * @return SeekerUsers
	 */
	public function setActivation($activation)
	{
		$this->activation = $activation;
	
		return $this;
	}
	
	/**
	 * Get activation
	 *
	 * @return string
	 */
	public function getActivation()
	{
		return $this->activation;
	}
	/**
	 * @return string
	 */
	public function getFirstname()
	{
		return $this->firstname;
	}
	/**
	 * Set params
	 *
	 * @param string $params
	 * @return SeekerUsers
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
	 * Set lastresettime
	 *
	 * @param \DateTime $lastresettime
	 * @return SeekerUsers
	 */
	public function setLastresettime($lastresettime)
	{
		$this->lastresettime = $lastresettime;
	
		return $this;
	}
	
	/**
	 * Get lastresettime
	 *
	 * @return \DateTime
	 */
	public function getLastresettime()
	{
		return $this->lastresettime;
	}
	
	/**
	 * Set resetcount
	 *
	 * @param integer $resetcount
	 * @return SeekerUsers
	 */
	public function setResetcount($resetcount)
	{
		$this->resetcount = $resetcount;
	
		return $this;
	}
	
	/**
	 * Get resetcount
	 *
	 * @return integer
	 */
	public function getResetcount()
	{
		return $this->resetcount;
	}
	/**
	 * @param string $firstname
	 */
	public function setFirstname($firstname)
	{
		$this->firstname = $firstname;
		$this->name = $firstname;
		$this->registerdate = new \DateTime();
	}

	/**
	 * @return string
	 */
	public function getLastname()
	{
		return $this->lastname;
	}

	/**
	 * @param string $lastname
	 */
	public function setLastname($lastname)
	{
		$this->lastname = $lastname;
		$this->name = $this->getFirstname()." ".$lastname;
	}

	/**
	 * Get the full name of the user (first + last name)
	 * @return string
	 */
	public function getFullName()
	{
		return $this->getName();
	}

	/**
	 * @param string $facebookId
	 * @return void
	 */
	public function setFacebookId($facebookId)
	{
		$this->facebookId = $facebookId;
		$this->setUsername($facebookId);
	}

	/**
	 * @return string
	 */
	public function getFacebookId()
	{
		return $this->facebookId;
	}
	/**
	 * @param string $birthday
	 * @return void
	 */
	public function setBirthday($birthday)
	{
		$this->birthday = $birthday;
	}
	
	/**
	 * @return string
	 */
	public function getBirthday()
	{
		return $this->birthday;
	}
	/**
	 * @param string $location
	 * @return void
	 */
	public function setLocation($location) {
		$this->location = $location;
	}
	/**
	 * @return string
	 */
	public function getLocation() {
		return $this->location;
	}
	public function getAccountType() {
		return $this->accountType;
	}
	public function setAccountType($accountType) {
		$this->accountType = $accountType;
		return $this;
	}
		/**
	 * @param Array
	 */
	public function setFBData($fbdata)
	{

		if (isset($fbdata['id'])) {
			$this->setFacebookId($fbdata['id']);
			$this->addRole('ROLE_FACEBOOK');
		}
		if (isset($fbdata['first_name'])) {
			$this->setFirstname($fbdata['first_name']);
		}
		if (isset($fbdata['last_name'])) {
			$this->setLastname($fbdata['last_name']);
		}
		if (isset($fbdata['email'])) {
			$this->setEmail($fbdata['email']);
			$this->setUsername($fbdata['email']);
		}
		if (isset($fbdata['birthday'])) {
			$oldDate = $fbdata['birthday'];
			$arr = explode('/', $oldDate);
			$newDate = $arr[1].'-'.$arr[0].'-'.$arr[2];
			$date = new DateTime($newDate);
			$this->setBirthday($date);
		}		
		if (isset($fbdata['location'])) {
			$this->setLocation($fbdata['location']['name']);
		}
	}

}