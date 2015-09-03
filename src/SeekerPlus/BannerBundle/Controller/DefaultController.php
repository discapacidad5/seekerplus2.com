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
					'SELECT b.id 
					FROM BannerBundle:Banner b 
					WHERE b.publishDown >=:date'
		)->setParameter('date',$date);
		
		$banners_id = $query->getResult();
		$quanty = count($banners_id);
		$banners_public = array();
		$banners = array();
		$ids_banners = array();

		for ($i=0; $i < 3; $i++) { 
			array_push($ids_banners, $banners_id[rand(0, $quanty-1)]);
		}

		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
						'SELECT b 
						FROM BannerBundle:Banner b 
						WHERE b.publishDown >=:date and b.id = :id'
			)->setParameter('date',$date)
			->setParameter('id',$ids_banners[0])
			->setMaxResults(1);
		array_push($banners, $query->getResult());

		$query = $em->createQuery(
						'SELECT b 
						FROM BannerBundle:Banner b 
						WHERE b.publishDown >=:date and b.id = :id'
			)->setParameter('date',$date)
			->setParameter('id',$ids_banners[1])
			->setMaxResults(1);
		array_push($banners, $query->getResult());

		$query = $em->createQuery(
						'SELECT b 
						FROM BannerBundle:Banner b 
						WHERE b.publishDown >=:date and b.id = :id'
			)->setParameter('date',$date)
			->setParameter('id',$ids_banners[2])
			->setMaxResults(1);
		array_push($banners, $query->getResult());
		
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
