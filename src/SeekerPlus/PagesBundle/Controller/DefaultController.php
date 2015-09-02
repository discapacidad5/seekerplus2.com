<?php

namespace SeekerPlus\PagesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \DateTime;
use SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerCities;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PagesBundle:Default:index.html.twig');
    }
    public function appAction()
    {
    	
    	if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
    		return $this->redirectToRoute('fos_user_security_login');
    	}
    	
    	$categories=$this->getDoctrine()
    	->getRepository("AdsmanagerBundle:AdsmanagerCategories");
    	$query = $categories->createQueryBuilder('a')
    	->addOrderBy('a.ordering', 'ASC')
    	->getQuery();
    	 
    	$categories = $query->getResult();

    	$cities=new AdsmanagerCities();
    	
    	$adCities=$cities->getAllCities($this->getDoctrine()->getEntityManager());
    	
		$userLocation = $this->get('security.context')->getToken()->getUser()->getLocation();
		$locationCity=$cities->getAdCity($this->getDoctrine()->getEntityManager(),$userLocation,$this);
    	
    	$em = $this->getDoctrine()->getManager();
    	$query = $em->createQuery(
    			'SELECT a
				    FROM AdsmanagerBundle:AdsmanagerAds a
				    WHERE a.published = 1
    				AND a.expirationDate >= :date
    			    AND a.adLocation = :location'
    	
    	)->setParameter('date',new DateTime())->setParameter('location',$locationCity->getTitle());
    	
    	$ads = $query->getResult();

    	return $this->render('AdsmanagerBundle:Map:ads.html.twig',array("categories"=>$categories,"ads"=>$ads,"location"=>$locationCity,"cities"=>$adCities));
    }

    public function appCityAction($city)
    {
    	 
    	if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
    		return $this->redirectToRoute('fos_user_security_login');
    	}
    	 
    	$categories=$this->getDoctrine()
    	->getRepository("AdsmanagerBundle:AdsmanagerCategories");
    	$query = $categories->createQueryBuilder('a')
    	->addOrderBy('a.ordering', 'ASC')
    	->getQuery();
    
    	$categories = $query->getResult();
	 
		$cities=new AdsmanagerCities();
		
		$adCities=$cities->getAllCities($this->getDoctrine()->getEntityManager());
		$locationCity=$cities->setAdCity($this->getDoctrine()->getEntityManager(),$city);
    	 
    	$em = $this->getDoctrine()->getManager();
    	$query = $em->createQuery(
    			'SELECT a
				    FROM AdsmanagerBundle:AdsmanagerAds a
				    WHERE a.published = 1
    				AND a.expirationDate >= :date
    			    AND a.adLocation = :location'
   
    	)->setParameter('date',new DateTime())->setParameter('location',$locationCity->getTitle());
    	 
    	$ads = $query->getResult();
    
    	return $this->render('AdsmanagerBundle:Map:ads.html.twig',array("categories"=>$categories,"ads"=>$ads,"location"=>$locationCity,"cities"=>$adCities));
    }
    
    public function contactenosAction()
    {
    	return $this->render('PagesBundle:Default:contactenos.html.twig');
    }
    public function serviciosAction()
    {
    	return $this->render('PagesBundle:Default:servicios.html.twig');
    }
    public function politicasDePrivacidadAction()
    {
    	return $this->render('PagesBundle:Default:politicasDePrivacidad.html.twig');
    }
    public function condicionesDeUsoAction()
    {
    	return $this->render('PagesBundle:Default:condicionesDeUso.html.twig');
    }

}
