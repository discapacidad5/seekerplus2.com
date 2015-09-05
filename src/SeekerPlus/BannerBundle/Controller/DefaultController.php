<?php

namespace SeekerPlus\BannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;
use SeekerPlus\AdsmanagerBundle\Models\Formdata;

class DefaultController extends Controller
{
    public function showAction($id)
    {
    	$date = new DateTime();
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
					'SELECT b
				    FROM BannerBundle:Banner b
				    WHERE b.id = :id
					AND b.publishDown >=:date'
		)->setParameter('id',$id)->setParameter('date',$date);
		
		$banners = $query->getResult();
		if(!$banners)
			return $this->render('BannerBundle:Default:noExist.html.twig');
			
		foreach($banners as $banner) {
			$obj = json_decode($banner->getParams());
			$image=$obj->{'imageurl'};
		}
		
		$this->setClickBanner ($id);
		$currenDate = new DateTime();
		$date = $currenDate->diff($banner->getPublishDown());
		$time=array("days"=>$date->d, "hours"=>$date->h, "minutes"=>$date->i);
    	
        return $this->render('BannerBundle:Default:show.html.twig', array('banner' => $banner,'image' => $image,'time' => $time));
    }

    public function showBannersAction(Request $request)
    {
    	$banners = array();
		$banners_public = array();

		$request = $this->container->get('request');
        $city = $request->request->get('city');
        
		$ids_banners = $this->getIdsBanners($city);
		for($i = 0; $i< count($ids_banners); $i++){
			array_push($banners, $this->showBanner($ids_banners[$i]));
		}

		$currenDate = new DateTime();
		
		foreach($banners as $banner) {
				$obj = json_decode($banner[0]->getParams());
				$image = $obj->{'imageurl'};
				$date = $currenDate->diff($banner[0]->getPublishDown());
				$banner_plu = array(
					'id' => $banner[0]->getId(),
					'imageurl' => $image,
					'days' => $date->d,
					'hours' => $date->h,
					'minutes' => $date->i,
					);
				array_push($banners_public, $banner_plu);
			}
    	
		if(!$banners)
			return $this->render('BannerBundle:Default:noExist2.html.twig');
			
        return $this->render('BannerBundle:Default:banners.html.twig', array('banners' => $banners_public));
    }
	/**
	 * 
	 */private function setClickBanner($id) {
		$banner=$this->getDoctrine()
		->getRepository("BannerBundle:Banner")
		->find($id);
		
		$formData=new Formdata();
		$banner->setClicks($banner->getClicks()+1);
		$formData->updateData($this);
	}

	private function setPrintBanner($id) {
		$banner=$this->getDoctrine()
		->getRepository("BannerBundle:Banner")
		->find($id);
		
		$formData = new Formdata();
		$banner->setImpmade($banner->getImpmade()+1);
		$formData->updateData($this);
	}

	private function checkPrintingr($id){
		$banner=$this->getDoctrine()
		->getRepository("BannerBundle:Banner")
		->find($id);
		$print = array($banner->getImptotal(), $banner->getImpmade());
		if($print[0] > $print[1] || $print[0] == 0)
			return true;
		else
			return false;

	}

	private function getIdsBanners($city){
		$date = new DateTime();
    	$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
					'SELECT b.id 
					FROM BannerBundle:Banner b, AdsmanagerBundle:AdsmanagerCities c
					WHERE b.publishDown >=:date and b.state = 1 and b.catid = c.id and c.title = :title'
		)->setParameter('date',$date)
		->setParameter('title',$city);
		
		$banners_id = $query->getResult();
		$quanty = count($banners_id);
		$ids_banners = array();
		$maximo = 3;
		if ($quanty < 3){
			$maximo = $quanty;
			for($i = 0; $i < $maximo; $i++){
				$id_nuevo = $banners_id[$i];
				$print_banner = $this->checkPrintingr($id_nuevo);
				if($print_banner){
					array_push($ids_banners, $id_nuevo);
				}
			}

		}else{ 
		for ($i = 0; $i < 3; $i++) { 
			$search_other = 0;
			$id_nuevo = $banners_id[rand(0, $quanty-1)];
			$print_banner = $this->checkPrintingr($id_nuevo);
			foreach ($ids_banners as $id) {
				if($id == $id_nuevo){
					$i = $i - 1;
					$search_other = 1;
				}
			}
			if($search_other == 0){
				if($print_banner){
					array_push($ids_banners, $id_nuevo);
				}else{
					$i = $i - 1;
					
				}
			}
			
		}
	}
		return $ids_banners;
	}

	private function showBanner($id){
		$date = new DateTime();
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
						'SELECT b 
						FROM BannerBundle:Banner b 
						WHERE b.publishDown >=:date and b.id = :id'
			)->setParameter('date',$date)
			->setParameter('id',$id)
			->setMaxResults(1);
		$this->setPrintBanner($id);
		return $query->getResult();
	}

}
