<?php

namespace SeekerPlus\AdsmanagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerCategories;
use SeekerPlus\AdsmanagerBundle\Form\AdsmanagerAdsType;
use SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerAds;
use SeekerPlus\AdsmanagerBundle\Models\Formdata;
use SeekerPlus\AdsmanagerBundle\Models\Message;
use SeekerPlus\AdsmanagerBundle\Models\Document;
use \DateTime;
use \DateInterval;
use Symfony\Component\HttpFoundation\File\File;
use SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerCities;

class AdsCategoriesController extends Controller
{
	public function showAction($idCategory,$idCity,$range,Request $request)
	{
		if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
			return $this->redirectToRoute('fos_user_security_login');
		}

		$category=$this->getDoctrine()
		->getRepository("AdsmanagerBundle:AdsmanagerCategories")->findOneById($idCategory);

		$city=$this->getDoctrine()
		->getRepository("AdsmanagerBundle:AdsmanagerCities")->findOneById($idCity);
		
		$ads = $this->getAdsCategory ($city,$idCategory,$range);

		$cities=new AdsmanagerCities();
		 
		$adCities=$cities->getAllCities($this->getDoctrine()->getEntityManager());

		$locationCity=$cities->getAdCity($this->getDoctrine()->getEntityManager(),$city->getTitle(),$this);
		
		$categories=$this->getDoctrine()
		->getRepository("AdsmanagerBundle:AdsmanagerCategories");
		$query = $categories->createQueryBuilder('a')
		->addOrderBy('a.ordering', 'ASC')
		->getQuery();
		
		$categories = $query->getResult();
		
		if(!$ads){
			return $this->render('AdsmanagerBundle:Categories:dontExits.html.twig',array("categories"=>$categories,"cities"=>$adCities,"location"=>$locationCity));
		}
		$adsFullData = array();
		foreach ($ads as $ad){
			array_push($ad,$this->getRatedAds($ad['0']));
			array_push($adsFullData,$ad);
		}

		return $this->render('AdsmanagerBundle:Categories:show.html.twig',
				array("categories"=>$categories,"cities"=>$adCities,"location"=>$locationCity,"ads"=>$adsFullData,"category"=>$category,"city"=>$city,"range"=>$range));

	}
	public function showRatedAction($idCategory,$idCity,$range,Request $request)
	{
		if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
			return $this->redirectToRoute('fos_user_security_login');
		}
	
		$category=$this->getDoctrine()
		->getRepository("AdsmanagerBundle:AdsmanagerCategories")->findOneById($idCategory);
	
		$city=$this->getDoctrine()
		->getRepository("AdsmanagerBundle:AdsmanagerCities")->findOneById($idCity);
	
		$ads = $this->getAdsCategoryRated ($city,$idCategory,$range);
	
		$cities=new AdsmanagerCities();
			
		$adCities=$cities->getAllCities($this->getDoctrine()->getEntityManager());
	
		$locationCity=$cities->getAdCity($this->getDoctrine()->getEntityManager(),$city->getTitle(),$this);
	
		$categories=$this->getDoctrine()
		->getRepository("AdsmanagerBundle:AdsmanagerCategories");
		$query = $categories->createQueryBuilder('a')
		->addOrderBy('a.ordering', 'ASC')
		->getQuery();
	
		$categories = $query->getResult();
	
		if(!$ads){
			return $this->render('AdsmanagerBundle:Categories:dontExits.html.twig',array("categories"=>$categories,"cities"=>$adCities,"location"=>$locationCity));
		}
		$adsFullData = array();
		foreach ($ads as $ad){
			array_push($ad,$this->getRatedAds($ad['0']));
			array_push($adsFullData,$ad);
		}
	
		return $this->render('AdsmanagerBundle:Categories:show.html.twig',
				array("categories"=>$categories,"cities"=>$adCities,"location"=>$locationCity,"ads"=>$adsFullData,"category"=>$category,"city"=>$city,"range"=>$range,"rated"=>true));
	
	}
	public function showGeolocationAction($idCategory,$idCity,$latitude,$longitude,$range,Request $request)
	{
		if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
			return $this->redirectToRoute('fos_user_security_login');
		}
	
		$category=$this->getDoctrine()
		->getRepository("AdsmanagerBundle:AdsmanagerCategories")->findOneById($idCategory);
	
		$city=$this->getDoctrine()
		->getRepository("AdsmanagerBundle:AdsmanagerCities")->findOneById($idCity);
	
		$ads = $this->getAdsCategoryGeolocation($latitude,$longitude,$city,$idCategory,$range);
	
		$cities=new AdsmanagerCities();
			
		$adCities=$cities->getAllCities($this->getDoctrine()->getEntityManager());
	
		$locationCity=$cities->getAdCity($this->getDoctrine()->getEntityManager(),$city->getTitle(),$this);
	
		$categories=$this->getDoctrine()
		->getRepository("AdsmanagerBundle:AdsmanagerCategories");
		$query = $categories->createQueryBuilder('a')
		->addOrderBy('a.ordering', 'ASC')
		->getQuery();
	
		$categories = $query->getResult();
	
		if(!$ads){
			return $this->render('AdsmanagerBundle:Categories:dontExits.html.twig',array("categories"=>$categories,"cities"=>$adCities,"location"=>$locationCity,"latitude"=>$latitude,"longitude"=>$longitude));
		}
		$adsFullData = array();
		foreach ($ads as $ad){
			array_push($ad,$this->getRatedAds($ad['0']));
			array_push($adsFullData,$ad);
		}
	
		return $this->render('AdsmanagerBundle:Categories:show.html.twig',
				array("categories"=>$categories,"cities"=>$adCities,"location"=>$locationCity,"latitude"=>$latitude,"longitude"=>$longitude,"ads"=>$adsFullData,"category"=>$category,"city"=>$city,"range"=>$range));
	
	}
	public function showMapAction($idCategory,$idCity,Request $request)
	{
		if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
			return $this->redirectToRoute('fos_user_security_login');
		}

		$city=$this->getDoctrine()
		->getRepository("AdsmanagerBundle:AdsmanagerCities")->findOneById($idCity);
	
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
				'SELECT a
				    FROM AdsmanagerBundle:AdsmanagerAds a
				    INNER JOIN a.catid c
				    INNER JOIN AdsmanagerBundle:AdsmanagerCategories b
				    WHERE a.published = 1
    				AND a.expirationDate >= :date
    			    AND a.adLocation = :location
				    AND b.parent =:parent
				    AND b.id = c.id
				    ORDER BY a.adHeadline ASC
				 '
		
		)->setParameter('date',new DateTime())->setParameter('location',$city->getTitle())
		->setParameter('parent',$idCategory);
		
		$ads = $query->getResult();
		
		$cities=new AdsmanagerCities();
			
		$adCities=$cities->getAllCities($this->getDoctrine()->getEntityManager());
	
		$locationCity=$cities->getAdCity($this->getDoctrine()->getEntityManager(),$city->getTitle(),$this);
	
		$categories=$this->getDoctrine()
		->getRepository("AdsmanagerBundle:AdsmanagerCategories");
		$query = $categories->createQueryBuilder('a')
		->addOrderBy('a.ordering', 'ASC')
		->getQuery();
	
		$categories = $query->getResult();
	
		if(!$ads){
			return $this->render('AdsmanagerBundle:Categories:dontExits.html.twig',array("categories"=>$categories,"cities"=>$adCities,"location"=>$locationCity,"latitude"=>0,"longitude"=>0));
		}
	
		return $this->render('AdsmanagerBundle:Map:ads.html.twig',array("categories"=>$categories,"ads"=>$ads,"location"=>$locationCity,"cities"=>$adCities));

	}
	private function getAdsCategory($city,$idCategory,$range) {
	 

	 $em = $this->getDoctrine()->getManager();
	 $query = $em->createQuery(
	 		'SELECT a, a.id
				    FROM AdsmanagerBundle:AdsmanagerAds a
				    INNER JOIN a.catid c
				    INNER JOIN AdsmanagerBundle:AdsUsers u WITH u.id = a.userid
				    INNER JOIN AdsmanagerBundle:AdsmanagerCategories b
				    WHERE a.published = 1
    				AND a.expirationDate >= :date
    			    AND a.adLocation = :location
				    AND b.parent =:parent
				    AND b.id = c.id
				    ORDER BY u.accounttype DESC, a.adHeadline ASC
				 '
  
	 )->setParameter('date',new DateTime())->setParameter('location',$city->getTitle())
	 ->setParameter('parent',$idCategory)->setMaxResults(10)->setFirstResult($range);

	 $ads = $query->getResult();

	 return $ads;
	}
	
	private function getAdsCategoryRated($city,$idCategory,$range) {

		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
				'SELECT a,a.id
				    FROM AdsmanagerBundle:AdsmanagerAds a
				    INNER JOIN a.catid c
				    INNER JOIN AdsmanagerBundle:AdsmanagerCategories b
				    WHERE a.published = 1
    				AND a.expirationDate >= :date
    			    AND a.adLocation = :location
				    AND (b.parent =:category
					OR (c.id =:category))
				    AND b.id = c.id
				    ORDER BY a.rated DESC
				 '
	
		)->setParameter('date',new DateTime())->setParameter('location',$city->getTitle())
		->setParameter('category',$idCategory)->setMaxResults(10)->setFirstResult($range);
	
		$ads = $query->getResult();
		return $ads;
	}
	
	private function getAdsCategoryGeolocation($latitude,$longitude,$city,$idCategory,$range) {

			$em = $this->getDoctrine()->getManager();
			$query = $em->createQuery(
					'SELECT a,( 3959 * acos(cos(radians('.$latitude.'))' .
					'* cos( radians( a.adLatitude ) )' .
					'* cos( radians( a.adLongitude )' .
					'- radians('.$longitude.') )' .
					'+ sin( radians('.$latitude.') )' .
					'* sin( radians( a.adLatitude ) ) ) )*1000 AS distance
				    FROM AdsmanagerBundle:AdsmanagerAds a
				    INNER JOIN a.catid c
				    INNER JOIN AdsmanagerBundle:AdsmanagerCategories b
				    WHERE a.published = 1
    				AND a.expirationDate >= :date
    			    AND a.adLocation = :location
				    AND (b.parent =:category
					OR (c.id =:category))
				    AND b.id = c.id
				    ORDER BY distance ASC
				 '
	 
			)->setParameter('date',new DateTime())->setParameter('location',$city->getTitle())
			->setParameter('category',$idCategory)->setMaxResults(10)->setFirstResult($range);

		$ads = $query->getResult();
		return $ads;
	}
	private function getRatedAds($idAds){
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
				'SELECT round(avg(a.rate))as rate,count(a.rate) as score FROM AdsmanagerBundle:AdsmanagerAdsRate a
				    WHERE a.idAds = :idAds
				 '
	
		)->setParameter('idAds',$idAds);
	
		$rateds = $query->getResult();
	
		return $rateds;
	}
}
