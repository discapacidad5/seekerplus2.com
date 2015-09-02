<?php

namespace SeekerPlus\AdsmanagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
use SeekerPlus\AdsmanagerBundle\Entity\Search;

class AdsSearchController extends Controller
{
	public function searchAction(Request $request)
    {
        if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirectToRoute('fos_user_security_login');
        }
        $request = $this->container->get('request');
        $texto = $request->request->get('texto');
        $city = $request->request->get('city');
        $id_city = $request->request->get('id_city');
        $date = new \DateTime();

        $quantity_result = 10;
        
        if($texto == ''){
            return new Response('');
        }
        $repo = $this->getDoctrine()->getManager();
        
        $query = $repo->createQuery('
               select a from AdsmanagerBundle:AdsmanagerCategories a 
               where a.metadataKeywords LIKE :title or a.name Like :name')
                ->setParameter('title', '%'.$texto.'%')
                ->setParameter('name', '%'.$texto.'%')
                ->setMaxResults($quantity_result);
        $categories = $query->getResult();

        $quantity_result = $quantity_result - count($categories);
        
        $query = $repo->createQuery('
               select a from AdsmanagerBundle:AdsmanagerAds a 
               where (a.adKeywords LIKE :key or a.adHeadline LIKE :title) and a.adLocation = :city and a.published = 1 and a.expirationDate > :date')
                ->setParameter('date', $date->format('Y-m-d'))
                ->setParameter('key', '%'.$texto.'%')
                ->setParameter('title', '%'.$texto.'%')
                ->setParameter('city', $city)
                
                ->setMaxResults($quantity_result);
        $ads = $query->getResult();
        
        $quantity_result = $quantity_result - count($ads);
        
        $query = $repo->createQuery('
               select a.id, a.name, a.idAd, b.adHeadline from AdsmanagerBundle:AdsmanagerProduct a, AdsmanagerBundle:AdsmanagerAds b
               where a.name LIKE :name and a.idAd = b.id and b.adLocation = :city and b.published = 1 and b.expirationDate > :date group by b.adHeadline')
                ->setParameter('name', '%'.$texto.'%')
                ->setParameter('city', $city)
                ->setParameter('date', $date->format('Y-m-d'))
                ->setMaxResults($quantity_result);
        $products = $query->getResult();

        foreach ($products as $product) {
            foreach ($ads as $ads_uni) {
                if($product['idAd'] == $ads_uni->getId()){
                   array_pop($products);
                }
            }
        }
        return $this->render('AdsmanagerBundle:AdsSearch:lista.html.twig', array("ads"=>$ads,"categories"=>$categories,"products"=>$products, 'id_city' => $id_city));
    }

    public function searchCategoryAction($idCategory,$idCity,$range,Request $request)
    {
        if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirectToRoute('fos_user_security_login');
        }

        $category=$this->getDoctrine()
        ->getRepository("AdsmanagerBundle:AdsmanagerCategories")->findOneById($idCategory);
        
        if($category->getParent() == '0'){
           return $this->forward('AdsmanagerBundle:AdsCategories:show',array('idCategory' => $idCategory, 'idCity' => $idCity, 'range' => $range));
        }
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

   private function getAdsCategory($city,$idCategory,$range) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                'SELECT a,a.id
                    FROM AdsmanagerBundle:AdsmanagerAds a
                    INNER JOIN a.catid c
                    INNER JOIN AdsmanagerBundle:AdsmanagerCategories b
                    WHERE a.published = 1
                    AND a.expirationDate >= :date
                    AND a.adLocation = :location
                    AND b.id =:id
                    AND b.id = c.id
                    ORDER BY a.adHeadline ASC
            ')->setParameter('date',new DateTime())->setParameter('location',$city->getTitle())->setParameter('id',$idCategory)->setMaxResults(10)->setFirstResult($range);
        
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
                    AND b.id =:parent
                    AND b.id = c.id
                    ORDER BY a.rated DESC
                 '
    
        )->setParameter('date',new DateTime())->setParameter('location',$city->getTitle())
        ->setParameter('parent',$idCategory)->setMaxResults(10)->setFirstResult($range);
    
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
                    AND b.id =:id
                    AND b.id = c.id
                    ORDER BY distance ASC
                 '
     
            )->setParameter('date',new DateTime())->setParameter('location',$city->getTitle())
            ->setParameter('id',$idCategory)->setMaxResults(10)->setFirstResult($range);

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

    public function searchSaveAction(Request $request)
    {
        if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirectToRoute('fos_user_security_login');
        }
        
        $request = $this->container->get('request');
        $search = $request->request->get('write');
        $result = $request->request->get('title');
        $date = new \DateTime();

        $search_ads = new Search();

        $ip = $this->container->get('request')->getClientIp();
        $search_ads->setDateSearch($date);
        $search_ads->setAccessIp($ip);
        $search_ads->setSearch($search);
        $search_ads->setResult($result);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($search_ads);
        $em->flush();

        return new Response('Created product id '.$search_ads->getId());
    }
}