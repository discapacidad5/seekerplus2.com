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
