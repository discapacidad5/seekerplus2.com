<?php

namespace SeekerPlus\AdsmanagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerCategories;
use SeekerPlus\AdsmanagerBundle\Form\AdsmanagerAdsType;
use SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerAds;
use SeekerPlus\AdsmanagerBundle\Entity\AdsComments;
use SeekerPlus\AdsmanagerBundle\Entity\AdsInbox;
use SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerAdsRate;
use SeekerPlus\AdsmanagerBundle\Models\Formdata;
use SeekerPlus\AdsmanagerBundle\Models\Message;
use SeekerPlus\AdsmanagerBundle\Models\Document;
use SeekerPlus\UserBundle\Entity\User;
use \DateTime;
use \DateInterval;
use Symfony\Component\HttpFoundation\File\File;
use SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerCities;

class AdsController extends Controller
{
	public function myAdsAction(Request $request)
	{
		if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
			return $this->redirectToRoute('fos_user_security_login');
		}

		$userId = $this->get('security.context')->getToken()->getUser()->getId();
		
		$ads= $this->getDoctrine()
		->getRepository("AdsmanagerBundle:AdsmanagerAds")
		->findByUserid($userId);
		
		foreach ($ads as $ad){
			$products= $this->getDoctrine()
			->getRepository("AdsmanagerBundle:AdsmanagerProduct")
			->findByIdAd($ad->getId());
			foreach ($products as $product){
				
				$ad->setProducts (array('id' => $product->getId(),
						             'name' => $product->getName(),
									 'description' => $product->getDescription(),
						             'price' => $product->getPrice(),
						             'images' => $product->getImages()
				));
				
			}
			
		}
		
		$userEmail = $this->get('security.context')->getToken()->getUser()->getEmail();
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
					'SELECT b
				    FROM BannerBundle:Banner b,BannerBundle:BannerClient c
				    WHERE c.email = :email'
				
		)->setParameter('email',$userEmail);
		
		$banners = $query->getResult();
		$image="";
		foreach($banners as $banner) {
			$obj = json_decode($banner->getParams());
			$image=$obj->{'imageurl'};
			$banner->setParams($image);
		}
		$user=$this->getDoctrine()->getRepository("AdsmanagerBundle:AdsUsers")->find($userId);
		$userType=$user->getAccounttype();
<<<<<<< HEAD

        $numberAds = $this->getDoctrine()->getManager();

        $query = $numberAds->createQuery('SELECT COUNT(a.userid) FROM AdsmanagerBundle:AdsmanagerAds a 
                                          Where a.userid= :id')->setParameter('id',$userId);
        $numberAdsResult = $query->getSingleScalarResult();


        $queryU = $numberAds->createQuery('SELECT a.id FROM AdsmanagerBundle:AdsmanagerAds a 
                                          Where a.userid= :idUser')->setParameter('idUser',$userId);
        $result = $queryU->getResult();

        $numberProducts=0;
        foreach ($result as $row) {
            $queryn = $numberAds->createQuery('SELECT COUNT(a.idAd) FROM AdsmanagerBundle:AdsmanagerProduct a 
            Where a.idAd= :id')->setParameter('id',$row['id']);
            $numberProductResult = $queryn->getSingleScalarResult();
            $numberProducts=$numberProductResult + $numberProducts;
        }

		
		return $this->render('AdsmanagerBundle:Ads:myAds.html.twig',
				array("ads"=>$ads,"banners"=>$banners,"userType"=>$userType,'numberAds' => $numberAdsResult,'numberProducts' =>$numberProducts));
=======
		
		return $this->render('AdsmanagerBundle:Ads:myAds.html.twig',
				array("ads"=>$ads,"banners"=>$banners,"userType"=>$userType));
>>>>>>> 9df963ee2e82d93855f7924fa180559a6a268b0f

	}

    public function notificationAction(){

   }
public function adCommentAction(Request $request){

     if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirectToRoute('fos_user_security_login');
        }
        
        $userId = $this->get('security.context')->getToken()->getUser()->getId();
        $userName= $this->get('security.context')->getToken()->getUser()->getName();
       if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
       
  
   

        $request = $this->container->get('request');
        $idAd = json_decode($request->request->get('idAd'));
        $comments= json_decode($request->request->get('comment'));

        $emComment = $this->getDoctrine()->getManager();
        $queryn =  $emComment->createQuery('SELECT COUNT(a.idAds) FROM AdsmanagerBundle:AdsComments a 
                                     Where a.idAds= :id')
                                     ->setParameter('id',$idAd);
        $nComments = $queryn->getSingleScalarResult();
        $nComments = $nComments+1;

  
        $dateTime = new \DateTime();
        $date = $dateTime->format('d/m/y H:i:s');
 
        $ad_comment =new AdsComments();  
        $em = $this->getDoctrine()->getManager();
        $ad_comment->setIdUser($userId);
        $ad_comment->setIdAds($idAd);
        $ad_comment->setDateCreated($dateTime);
        $ad_comment->setCommentAd($comments);
        $em->persist($ad_comment);
        $em->flush();

   
        $response = array("date" =>     $date , "success" => true ,
        'idAd' =>  $idAd ,  'userId' => $userId ,'comment' => $comments ,'userName' => $userName 
        ,'idc' =>  $ad_comment->getId(),'nCommentsAds' => $nComments
        );
 
        return new JsonResponse($response);

   }

   public function editCommentAction(Request $request){
    if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
        return $this->redirectToRoute('fos_user_security_login');
    }
          $request = $this->container->get('request');
          $id_comment = json_decode($request->request->get('idComment'));
          $newComment = json_decode($request->request->get('newComment'));

        $userId = $this->get('security.context')->getToken()->getUser()->getId();
        $userC=$this->getDoctrine()->getRepository('AdsmanagerBundle:AdsComments')->find($id_comment);
       
        if ($userC->getIdUser() == $userId ) {

           $em = $this->getDoctrine()->getManager();
           $editComment = $em->getRepository('AdsmanagerBundle:AdsComments')->find($id_comment);
           $editComment->setCommentAd($newComment);
           $em->flush();

          }

          $response = array("comment" =>  $newComment );
          return new JsonResponse($response);
         

   }
     public function dateCommentAction(Request $request){
   if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
        return $this->redirectToRoute('fos_user_security_login');
    }
    $userId = $this->get('security.context')->getToken()->getUser()->getId();
    $id_comment = json_decode($request->request->get('idComment'));

    $userC=$this->getDoctrine()->getRepository('AdsmanagerBundle:AdsComments')->find($id_comment);
    if ($userC->getIdUser() == $userId ) {

         $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(
                'SELECT a.commentAd
                    FROM AdsmanagerBundle:AdsComments a
                    WHERE a.id = :id           
                 '
    
         )->setParameter('id', $id_comment);
         $comments = $query->getResult();
     }

    $response = array("comment" => $comments );
 
        return new JsonResponse($response);

   }

   public function deleteCommentAction(Request $request){
        if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
        return $this->redirectToRoute('fos_user_security_login');
    }
        
        $userId = $this->get('security.context')->getToken()->getUser()->getId();

        $request = $this->container->get('request');
        $id_comment = json_decode($request->request->get('idComment'));
        $id_Ad = json_decode($request->request->get('idAd'));


        $userC=$this->getDoctrine()->getRepository('AdsmanagerBundle:AdsComments')->find($id_comment);
        if ($userC->getIdUser() == $userId ) {

             $delete=$this->getDoctrine()->getRepository('AdsmanagerBundle:AdsComments')->find($id_comment);
             $em=$this->getDoctrine()->getManager();
             $em->remove($delete);
             $em->flush();
        }
       $emComment = $this->getDoctrine()->getManager();
        $queryn =  $emComment->createQuery('SELECT COUNT(a.idAds) FROM AdsmanagerBundle:AdsComments a 
                                     Where a.idAds= :id')
                                     ->setParameter('id', $id_Ad);
        $nComments = $queryn->getSingleScalarResult();
        $nComments-1;
 
   
       $response = array("idc" =>  $id_Ad ,"n" =>  $nComments);
       return new JsonResponse($response);


   }



    public function newAdsAction(Request $request)
    {
    	
    	if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
    		return $this->redirectToRoute('fos_user_security_login');
    	}
    	
    	$formData=new Formdata();
    	$ad=new AdsmanagerAds();
    	$message=new Message();


        
    	
    	$categories=$this->getDoctrine()
    	->getRepository("AdsmanagerBundle:AdsmanagerCategories");
    	$query = $categories->createQueryBuilder('a')
    	->addOrderBy('a.name', 'ASC')
    	->getQuery();
    	
    	$categories = $query->getResult();
    	
    	$cities=new AdsmanagerCities();
    	$userLocation = $this->get('security.context')->getToken()->getUser()->getLocation();
    	$locationCity=$cities->getAdCity($this->getDoctrine()->getEntityManager(),$userLocation);
    	
    	    	
    	$form=$this->createForm(new AdsmanagerAdsType($this->getDoctrine()->getEntityManager()),$ad,array('validation_groups'=>'add'));
    	if(!$formData->isSetFormat($request))
    	{
			return $this->render('AdsmanagerBundle:Ads:newAds.html.twig',array("form"=>$form->createView(),"categories"=>$categories,"location"=>$locationCity));
    	}

    	if(!$formData->isValidFormat($request,$form,$message))
    	{
    		$message->show($this);
    		return $this->render('AdsmanagerBundle:Ads:newAds.html.twig',array("form"=>$form->createView(),"categories"=>$categories,"location"=>$locationCity));
    	
    	}

    	$image = $request->files->get('imagen');

    	if(!$formData->isValidImage($image,$form,$ad,$message,'adHeadline')){
    		$message->show($this);
    		return $this->render('AdsmanagerBundle:Ads:newAds.html.twig',array("form"=>$form->createView(),"categories"=>$categories,"location"=>$locationCity));
     		
    	}
    	
         $date = new DateTime();
         $createDate = new DateTime();


        $userId = $this->get('security.context')->getToken()->getUser()->getId();
        $userType=$this->getDoctrine()->getRepository("AdsmanagerBundle:AdsUsers")->find($userId);
        $numberAds = $this->getDoctrine()->getManager();
        $query = $numberAds->createQuery('SELECT COUNT(a.userid) FROM AdsmanagerBundle:AdsmanagerAds a 
                                          Where a.userid= :id')->setParameter('id',$userId);
        $numberAdsResult = $query->getSingleScalarResult();


        if($userType->getAccounttype()==0 && $numberAdsResult <1){

                  $this->setAddAds($userId,$date,$formData,$ad,$image,$createDate,$message);
                 
        }elseif($userType->getAccounttype()==1 && $numberAdsResult <3) {
                    
                    $this->setAddAds($userId,$date,$formData,$ad,$image,$createDate,$message);
                    
        }elseif($userType->getAccounttype()==2 && $numberAdsResult <10){

                $this->setAddAds($userId,$date,$formData,$ad,$image,$createDate,$message);
                
        }elseif($userType->getAccounttype()==3){

                $this->setAddAds($userId,$date,$formData,$ad,$image,$createDate,$message);
        }else{
          $message->setErrorMessages("Tiene que actualizar su cuenta para publicar un nuevo anuncio.")->show($this);   
        }
        
    
    	
    	return $this->redirectToRoute('my_ads');
    }

    

    public function editAdsAction($id,Request $request)
    {
    	 
    	if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
    		return $this->redirectToRoute('fos_user_security_login');
    	}
    	
    	$ad=$this->getDoctrine()
    	->getRepository("AdsmanagerBundle:AdsmanagerAds")
    	->find($id);
    	
    	if(!$ad){
    		return $this->redirectToRoute('my_ads');
    	} 

    	if(!$this->isAnUserOwner ( $ad->getUserId() )){
    		return $this->redirectToRoute('my_ads');
    	}
    	
    	$formData=new Formdata();
    	$message=new Message();
    	 
    	$categories=$this->getDoctrine()
    	->getRepository("AdsmanagerBundle:AdsmanagerCategories");
    	$query = $categories->createQueryBuilder('a')
    	->addOrderBy('a.name', 'ASC')
    	->getQuery();
    	
    	$categories = $query->getResult();
    	
    	$cities=new AdsmanagerCities();
    	$userLocation = $this->get('security.context')->getToken()->getUser()->getLocation();
    	$locationCity=$cities->getAdCity($this->getDoctrine()->getEntityManager(),$userLocation);
     
    	$form=$this->createForm(new AdsmanagerAdsType($this->getDoctrine()->getEntityManager()),$ad,array('validation_groups'=>'add'));
    	if(!$formData->isSetFormat($request))
    	{
    		$form->handleRequest($request);
    		return $this->render('AdsmanagerBundle:Ads:editAds.html.twig',array("form"=>$form->createView(),"categories"=>$categories,"location"=>$locationCity,"image"=>$ad->getImages(),"id"=>$ad->getId()));
    	}
    
    	if(!$formData->isValidFormat($request,$form,$message))
    	{
    		$message->show($this);
    		return $this->render('AdsmanagerBundle:Ads:editAds.html.twig',array("form"=>$form->createView(),"categories"=>$categories,"location"=>$locationCity,"image"=>$ad->getImages(),"id"=>$ad->getId()));
    		 
    	}

    	$image = $request->files->get('imagen');
    
    	if(!$formData->isValidImageUpdate($image,$form,$ad,$message,'adHeadline')){
    		$message->show($this);
    		return $this->render('AdsmanagerBundle:Ads:editAds.html.twig',array("form"=>$form->createView(),"categories"=>$categories,"location"=>$locationCity,"image"=>$ad->getImages(),"id"=>$ad->getId()));
    		 
    	}
    	
    	$userId = $this->get('security.context')->getToken()->getUser()->getId();
    	$ad->setUserid($userId);
    	$ad->setPublished(0);
    	$date = new DateTime();
    	$ad->setDateModified($date);

   	    $formData->updateData($this);
    	$message->setSuccessMessages("El anuncio ha sido Modificado Exitosamente.")->show($this);
    	
    	if($image){
    	$formData->uploadImages($image,'images/ids/'.$ad->getId(),$ad);
    	$this->resizeImages($ad->getId(),$ad->getImages());
    	}
    	return $this->redirectToRoute('my_ads');
    }

    public function deleteAdsAction($id,Request $request)
    {
    	if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
    		return $this->redirectToRoute('fos_user_security_login');
    	}
    	
    	$ad=$this->getDoctrine()
    	->getRepository("AdsmanagerBundle:AdsmanagerAds")
    	->find($id);
    	
        if(!$ad){
    		return $this->redirectToRoute('my_ads');
    	} 
    	
    	if(!$this->isAnUserOwner ( $ad->getUserId() )){
    		return $this->redirectToRoute('my_ads');
    	}
    	
    	$adProducts=$this->getDoctrine()
    	->getRepository("AdsmanagerBundle:AdsmanagerProduct")
    	->findByIdAd($id);
    	
    	$formData=new Formdata();
    	foreach ($adProducts as $product){
    		$formData->deleteData($this,$product);
    	}

    	$message=new Message();
    	$document = new Document();
    	$document->deleteDir("/images/ids/".$ad->getId());
    	$formData->deleteData($this,$ad);

    	$message->setSuccessMessages("El anuncio ha sido Eliminado Exitosamente.")->show($this);

    	return $this->redirectToRoute('my_ads');
   
    }
    public function renewAdsAction($id,Request $request)
    {
    	if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
    		return $this->redirectToRoute('fos_user_security_login');
    	}
    	 
    	$ad=$this->getDoctrine()
    	->getRepository("AdsmanagerBundle:AdsmanagerAds")
    	->find($id);
    	 
    	if(!$ad){
    		return $this->redirectToRoute('my_ads');
    	}
    	
    	if(!$this->isAnUserOwner ( $ad->getUserId() )){
    		return $this->redirectToRoute('my_ads');
    	}
    	
    	$formData=new Formdata();
    	$message=new Message();

    	$date = new DateTime();
    	$ad->setExpirationDate($date->add(new DateInterval('P1Y')));
    	$formData->updateData($this);
    	$message->setSuccessMessages("El anuncio ha sido Renovado Exitosamente.")->show($this);
    
    	return $this->redirectToRoute('my_ads');
    	 
    }

     public function paginationAdAction(Request $request){


     if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirectToRoute('fos_user_security_login');
        }

        $userId = $this->get('security.context')->getToken()->getUser()->getId();
        
    
        $request = $this->container->get('request');
        $idAd = json_decode($request->request->get('idAd'));
        $range = json_decode($request->request->get('range'));
 
        $em2 = $this->getDoctrine()->getManager();
         $query2 = $em2->createQuery(
                'SELECT a
                    FROM AdsmanagerBundle:AdsComments a
                    WHERE a.idAds = :id       
                    ORDER BY a.id DESC
                 '
    
        )->setParameter('id',$idAd)->setFirstResult($range)->setMaxResults(10);
         $comments = $query2->getArrayResult();

         $em3 = $this->getDoctrine()->getManager();
         $query3 = $em3->createQuery(
                'SELECT b
                    FROM UserBundle:User b , AdsmanagerBundle:AdsComments a
                    WHERE a.idUser = b.id       
                 '
    
        );
         $user = $query3->getArrayResult();



   return new JsonResponse(array("ida" => $idAd,"range"=>$range,'comments'=>$comments
                                 ,"user"=>$user,'userId'=> $userId));
       
    

        

    }


    public function showAction($idAd,$idCity,Request $request){
        
        if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirectToRoute('fos_user_security_login');
        }
        
          $emComment = $this->getDoctrine()->getManager();
          $queryn =  $emComment->createQuery('SELECT COUNT(a.idAds) FROM AdsmanagerBundle:AdsComments a 
                                     Where a.idAds= :id')
                                     ->setParameter('id',$idAd);
          $nComments = $queryn->getSingleScalarResult();

         $userId = $this->get('security.context')->getToken()->getUser()->getId();
        ///////////////
  
         $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(
                'SELECT a
                    FROM AdsmanagerBundle:AdsComments a
                    WHERE a.idAds = :id       
                    ORDER BY a.id DESC
                 '
    
        )->setParameter('id',$idAd)->setFirstResult(0)->setMaxResults(10);
         $comments = $query->getResult();


         $em2 = $this->getDoctrine()->getManager();
         $query2 = $em->createQuery(
                'SELECT b
                    FROM UserBundle:User b , AdsmanagerBundle:AdsComments a
                    WHERE a.idUser = b.id       
                 '
    
        );
         $user = $query2->getResult();

//////////////////7

        $ad=$this->getDoctrine()
        ->getRepository("AdsmanagerBundle:AdsmanagerAds")->findOneById($idAd);
        
        $products= $this->getDoctrine()
            ->getRepository("AdsmanagerBundle:AdsmanagerProduct")
            ->findByIdAd($ad->getId());
            foreach ($products as $product){
        
                $ad->setProducts (array('id' => $product->getId(),
                        'name' => $product->getName(),
                        'description' => $product->getDescription(),
                        'price' => $product->getPrice(),
                        'images' => $product->getImages()
                ));
        
            }
        
        $city=$this->getDoctrine()
        ->getRepository("AdsmanagerBundle:AdsmanagerCities")->findOneById($idCity);
        
        $cities=new AdsmanagerCities();
        
            
        $adCities=$cities->getAllCities($this->getDoctrine()->getEntityManager());
        
        $locationCity=$cities->getAdCity($this->getDoctrine()->getEntityManager(),$city->getTitle(),$this);
        
        $categories=$this->getDoctrine()
        ->getRepository("AdsmanagerBundle:AdsmanagerCategories");
        $query = $categories->createQueryBuilder('a')
        ->addOrderBy('a.ordering', 'ASC')
        ->getQuery();
        
        $categories = $query->getResult();
        
        $rated=$this->getRatedAds($idAd);
        
        if(!$ad){
            return $this->render('AdsmanagerBundle:Ads:dontExits.html.twig',array("categories"=>$categories,"cities"=>$adCities,"location"=>$locationCity));
        }
        
        
     return $this->render('AdsmanagerBundle:Ads:show.html.twig',
                array("categories"=>$categories,"cities"=>$adCities,"location"=>$locationCity,
                    "ad"=>$ad,"coments"=> $comments ,'activeUser' => $userId ,'users' => $user,
                    "idAd" => $idAd,"idCity" =>$idCity,"rated"=>$rated,"nComments"=>$nComments));
         
    }

    public function RateAction(Request $request){
    	
    	if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
    		return $this->redirectToRoute('fos_user_security_login');
    	}
    	
    	if (!$request->isXmlHttpRequest()) {
    		return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
    	}
    	
    	$request = $this->container->get('request');
    	$idAd = json_decode($request->request->get('idAd'));
    	$rate = json_decode($request->request->get('rate'));
    	
    	$adsRate=new AdsmanagerAdsRate();
    	$formData=new Formdata();
    	$userId = $this->get('security.context')->getToken()->getUser()->getId();
 	
    	if($this-> isRated($idAd,$userId)){
    		
    		$adsRate=$this->getDoctrine()
    		->getRepository("AdsmanagerBundle:AdsmanagerAdsRate")
    		->find($this->getRatedId($idAd,$userId));
    		$adsRate->setIdUser($userId);
    		$adsRate->setIdAds($idAd);
    		$adsRate->setRate($rate);
    		$formData->updateData($this);
    		$this->setRatedAds($idAd,$this->getRatedAdsValue($idAd));
    		$rated=$this->getRatedAds($idAd);
    		$response = array("code" => 100 , "success" => true ,"rated" => $rated[0]);
    		return new JsonResponse($response);
    	}

    	$adsRate->setIdUser($userId);
    	$adsRate->setIdAds($idAd);
    	$adsRate->setRate($rate);
    	$formData->insertData($this,$adsRate);
    	$this->setRatedAds($idAd,$this->getRatedAdsValue($idAd));
    	$rated=$this->getRatedAds($idAd);
    	$response = array("code" => 100 , "success" => true ,"rated" => $rated[0]);
    	return new JsonResponse($response);

    }
    public function showMapAction($idAd,$idCity,Request $request)
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
				    AND a.id =:id
				    AND b.id = c.id
				    ORDER BY a.adHeadline ASC
				 '
    
    	)->setParameter('date',new DateTime())->setParameter('location',$city->getTitle())
    	->setParameter('id',$idAd);
    
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
    		return $this->render('AdsmanagerBundle:Ads:dontExits.html.twig',array("categories"=>$categories,"cities"=>$adCities,"location"=>$locationCity,"latitude"=>0,"longitude"=>0));
    	}
    
    	return $this->render('AdsmanagerBundle:Map:ads.html.twig',array("categories"=>$categories,"ads"=>$ads,"location"=>$locationCity,"cities"=>$adCities));
    
    }
    private function setNameIdImages($formData, $ads, $image) {
    	if($image){
	    	$originalName = $image->getClientOriginalName();
	    	$name_array = explode('.', $originalName);
	    	$file_type = $name_array[sizeof($name_array) - 1];
	    	$ads->setImages($ads->getId().".".$file_type);
    	}else {
    		$ads->setImages('noImages.jpg');
    		
    	}
    	$formData->updateData($this);
    }
    
    private function resizeImages($dir,$image){
    
    	
    	$container = $this->container;
    
    	$imagemanagerResponse = $container->get('liip_imagine.controller');
    
    	$filterConfiguration = $container->get('liip_imagine.filter.configuration');
    
    	$configuracion = $filterConfiguration->get('my_thumb');

    	$filterConfiguration->set('my_thumb', $configuracion);
    
    	$imagemanagerResponse->filterAction($this->getRequest(),'/images/ids/'.$dir.'/'.$image,'my_thumb');
    
    	$fileTemporal = new File('media/cache/my_thumb/images/ids/'.$dir.'/'.$image);
    
    	$fileTemporal->move('images/ids/'.$dir.'/',$image);
    	$document = new Document();
    	$document->deleteDir("/media/cache/my_thumb/images/ids/".$dir);

    }

    private function isAnUserOwner($adUserId) {
     $userId = $this->get('security.context')->getToken()->getUser()->getId();
     if($userId!=$adUserId){
     	return false;
     }
     	return true;
    }
    
    private function isRated($idAds,$idUser){
    	$em = $this->getDoctrine()->getManager();
    	$query = $em->createQuery(
    			'SELECT a FROM AdsmanagerBundle:AdsmanagerAdsRate a
				    WHERE a.idAds = :idAds
    				AND a.idUser = :idUser
				 '
    	
    	)->setParameter('idAds',$idAds)
    	->setParameter('idUser',$idUser);
    	
    	$rated = $query->getResult();
    	
    	if($rated)
    		return true;
    	return false;
    }
    private function getRatedId($idAds,$idUser){
    	$em = $this->getDoctrine()->getManager();
    	$query = $em->createQuery(
    			'SELECT a.id FROM AdsmanagerBundle:AdsmanagerAdsRate a
				    WHERE a.idAds = :idAds
    				AND a.idUser = :idUser
				 '
   
    	)->setParameter('idAds',$idAds)
    	->setParameter('idUser',$idUser);
    	 
    	$rateds = $query->getResult();
   	
    	return $rateds[0]['id'];
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
    
    private function getRatedAdsValue($idAds){
    	$em = $this->getDoctrine()->getManager();
    	$query = $em->createQuery(
    			'SELECT round(avg(a.rate))as rate,count(a.rate) as score FROM AdsmanagerBundle:AdsmanagerAdsRate a
				    WHERE a.idAds = :idAds
				 '
    
    	)->setParameter('idAds',$idAds);
    
    	$rateds = $query->getResult();
    
    	return $rateds[0]['rate'];
    }
    
    private function setRatedAds($idAds,$rated){
    	$formData=new Formdata();
    	$ad=$this->getDoctrine()
    	->getRepository("AdsmanagerBundle:AdsmanagerAds")
    	->find($idAds);
    	
    	$ad->setRated($rated);
    	$formData->updateData($this);
    	
    }


  
        private function setAddAds($userId,$date,$formData,$ad,$image,$createDate,$message){
      
                    $ad->setUserid($userId);
                    $ad->setDateCreated($date);
                    $ad->setExpirationDate($createDate->add(new DateInterval('P1Y')));  
                    $formData->insertData($this,$ad);
                    $message->setSuccessMessages("El anuncio ha sido Ingresado Exitosamente.")->show($this);
                    $this->setNameIdImages ( $formData, $ad, $image );
                    if($image){
                    $formData->uploadImages($image,'images/ids/'.$ad->getId(),$ad);
                    $this->resizeImages($ad->getId(),$ad->getImages());
                }
    
        }

}
