<?php

namespace SeekerPlus\BannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

    public function showBannersAction()
    {

    	$date = new DateTime();
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
					'SELECT b 
					FROM BannerBundle:Banner b 
					WHERE b.publishDown >=:date'
		)->setParameter('date',$date)
		->setMaxResults(3);;
		
		$banners = $query->getResult();
		$currenDate = new DateTime();
		$banners_public = array();	
		foreach($banners as $banner) {
			$obj = json_decode($banner->getParams());
			$image = $obj->{'imageurl'};
			$this->setClickBanner ($banner->getId());
			$date = $currenDate->diff($banner->getPublishDown());
			$banner_plu = array(
				'name' => $banner->getName(),
				'imageurl' => $image,
				'description' => $banner->getDescription(),
				'imageurl' => $image,
				'days' => $date->d,
				'hours' => $date->h,
				'minutes' => $date->i,
				);
			array_push($banners_public, $banner_plu);
		}
		if(!$banners)
			return $this->render('BannerBundle:Default:noExist.html.twig');
			
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

	

}
