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
use SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerProduct;
use SeekerPlus\AdsmanagerBundle\Form\AdsmanagerProductType;


class ProductController extends Controller
{

    public function newAdsProductAction($id,Request $request)
    {
    	if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
    		return $this->redirectToRoute('fos_user_security_login');
    	}
    	 
    	$formData=new Formdata();
    	$adProduct=new AdsmanagerProduct();
    	$message=new Message();

    	
    	$form=$this->createForm(new AdsmanagerProductType(),$adProduct);
    	if(!$formData->isSetFormat($request))
    	{
    		return $this->render('AdsmanagerBundle:Product:newProductAds.html.twig',array("form"=>$form->createView()));
    	}
    	
    	if(!$formData->isValidFormat($request,$form,$message))
    	{
    		$message->show($this);
    		return $this->render('AdsmanagerBundle:Product:newProductAds.html.twig',array("form"=>$form->createView()));
    		 
    	}
    	
    	$image = $request->files->get('imagen');
    	
    	if(!$formData->isValidImage($image,$form,$adProduct,$message,'name')){
    		$message->show($this);
    		return $this->render('AdsmanagerBundle:Product:newProductAds.html.twig',array("form"=>$form->createView()));
    		 
    	}



        $userId = $this->get('security.context')->getToken()->getUser()->getId();
        $userType=$this->getDoctrine()->getRepository("AdsmanagerBundle:AdsUsers")->find($userId);
        $numberAds = $this->getDoctrine()->getManager();
        $query = $numberAds->createQuery('SELECT a.id FROM AdsmanagerBundle:AdsmanagerAds a 
                                          Where a.userid= :idUser')->setParameter('idUser',$userId);
        $result = $query->getResult();

        $numberProducts=0;
        foreach ($result as $row) {
            $query = $numberAds->createQuery('SELECT COUNT(a.idAd) FROM AdsmanagerBundle:AdsmanagerProduct a 
            Where a.idAd= :id')->setParameter('id',$row['id']);
            $numberAdsResult = $query->getSingleScalarResult();
            $numberProducts=$numberAdsResult + $numberProducts;
        }

   
        $price=$request->request->get('price');
        
        if($userType->getAccounttype()==0 &&  $numberProducts <3)
            {   

             $this->setAddProduct($adProduct,$formData,$message,$price,$image,$id);
            }
        elseif($userType->getAccounttype()==1 &&  $numberProducts <10) 
            {
              $this->setAddProduct($adProduct,$formData,$message,$price,$image,$id);
            }
        elseif($userType->getAccounttype()==2 || $userType->getAccounttype()==3)
            {
               $this->setAddProduct($adProduct,$formData,$message,$price,$image,$id);
            }
        else{
          $message->setErrorMessages("Tiene que actualizar su cuenta para publicar un nuevo producto.")->show($this);   
        }
        
    	return $this->redirectToRoute('my_ads');

    }

    public function editAdsProductAction($id,Request $request)
    {
    	if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
    		return $this->redirectToRoute('fos_user_security_login');
    	}
    	
    	$adProduct=$this->getDoctrine()
    	->getRepository("AdsmanagerBundle:AdsmanagerProduct")
    	->find($id);
    	 
    	if(!$adProduct){
    		return $this->redirectToRoute('my_ads');
    	}
    	
    	$ad=$this->getDoctrine()
    	->getRepository("AdsmanagerBundle:AdsmanagerAds")
    	->find($adProduct->getIdAd());
    	
    	if(!$this->isAnUserOwner ( $ad->getUserId() )){
    		return $this->redirectToRoute('my_ads');
    	}

    	$formData=new Formdata();
    	$message=new Message();
    	
    	 
    	$form=$this->createForm(new AdsmanagerProductType(),$adProduct);
    	if(!$formData->isSetFormat($request))
    	{
    		return $this->render('AdsmanagerBundle:Product:editProductAds.html.twig',array("form"=>$form->createView(),"id"=>$adProduct->getIdAd(),"image"=>$adProduct->getImages(),"price"=>$adProduct->getPrice()));
    	}
    	 
    	if(!$formData->isValidFormat($request,$form,$message))
    	{
    		$message->show($this);
    		return $this->render('AdsmanagerBundle:Product:editProductAds.html.twig',array("form"=>$form->createView(),"id"=>$adProduct->getIdAd(),"image"=>$adProduct->getImages(),"price"=>$adProduct->getPrice()));
    		 
    	}
    	 
    	$image = $request->files->get('imagen');
    	 
    	if(!$formData->isValidImageUpdate($image,$form,$adProduct,$message,'name')){
    		$message->show($this);
    		return $this->render('AdsmanagerBundle:Product:editProductAds.html.twig',array("form"=>$form->createView(),"id"=>$adProduct->getIdAd(),"image"=>$adProduct->getImages(),"price"=>$adProduct->getPrice()));
    		 
    	}
    	$ad->setPublished(0);
    	$date = new DateTime();
    	$ad->setDateModified($date);
    	$formData->updateData($this);
    	
    	$price=$request->request->get('price');
    	$adProduct->setPrice(str_replace('.', '', $price));
    	$formData->updateData($this);
    	$message->setSuccessMessages("El Producto o servicio ha sido Modificado Exitosamente.")->show($this);
    	
    	if($image){
    		$formData->uploadImages($image,'images/ids/'.$adProduct->getIdAd(),$adProduct);
    		$this->resizeImages($adProduct->getIdAd(),$adProduct->getImages());
    	}

    	return $this->redirectToRoute('my_ads');
    }
    public function deleteProductAdsAction($id,Request $request)
    {
    	if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
    		return $this->redirectToRoute('fos_user_security_login');
    	}
    	 
    	$adProduct=$this->getDoctrine()
    	->getRepository("AdsmanagerBundle:AdsmanagerProduct")
    	->find($id);
    	 
    	if(!$adProduct){
    		return $this->redirectToRoute('my_ads');
    	}
    	
    	$ad=$this->getDoctrine()
    	->getRepository("AdsmanagerBundle:AdsmanagerAds")
    	->find($adProduct->getIdAd());
    	 
    	if(!$this->isAnUserOwner ( $ad->getUserId() )){
    		return $this->redirectToRoute('my_ads');
    	}
    	
    	$formData=new Formdata();
    	$message=new Message();
    	$document = new Document();
    	$document->deleteFile("/images/ids/".$adProduct->getIdAd()."/".$adProduct->getImages());
    	$formData->deleteData($this,$adProduct);
    	
    	$message->setSuccessMessages("El Producto o Servicio ha sido Eliminado Exitosamente.")->show($this);
    	
    	return $this->redirectToRoute('my_ads');
   	
    }

    private function setNameIdImages($formData, $product, $image) {
    	if($image){
	    	$originalName = $image->getClientOriginalName();
	    	$name_array = explode('.', $originalName);
	    	$file_type = $name_array[sizeof($name_array) - 1];
	    	$product->setImages("p".$product->getId().".".$file_type);
    	}else {
    		$product->setImages('noImages.jpg');
    		
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


        private function setAddProduct($adProduct,$formData,$message,$price,$image,$id){
      
 
        $adProduct->setPrice(str_replace('.', '', $price));
        $adProduct->setIdAd($id);
        $formData->insertData($this,$adProduct);
        $message->setSuccessMessages("El Producto o servicio ha sido Ingresado Exitosamente.")->show($this);
        $this->setNameIdImages ( $formData,$adProduct, $image );
         
        if($image){
            $formData->uploadImages($image,'images/ids/'.$id,$adProduct);
            $this->resizeImages($id,$adProduct->getImages());
        }

    }
    
}
