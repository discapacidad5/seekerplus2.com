<?php

namespace SeekerPlus\AdsmanagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerCities;
use Doctrine\ORM\EntityManager;

class AdsmanagerAdsType extends AbstractType
{
	
	protected $em;
	
	function __construct(EntityManager $em)
	{
		$this->em = $em;
	}
	
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$cities=new AdsmanagerCities();
        $builder
            ->add('catid')
            ->add('adLocation','choice', array(
                  'choices' =>$cities->getCities($this->em),
                  'multiple' => false,'empty_value' => 'Choose an option','label' => 'form.location', 'translation_domain' => 'FOSUserBundle','attr'=>  array(
              		'class'   => 'js-select','id' =>'categories','data-placeholder'=>'Seleccione Ciudad','style'=>'width: 13rem;')
              ))
            ->add('adHeadline','text',array(
			 'label' => 'form.adHeadline', 'translation_domain' => 'FOSUserBundle','attr'=>  array(
              		'class'   => 'userForm')
              ))
            ->add('adKeywords','textarea',array(
			 'label' => 'form.adKeywords', 'translation_domain' => 'FOSUserBundle','attr'=>  array(
              		'class'   => 'userForm')
              ))
            ->add('adText','textarea',array(
			 'label' => 'form.adText', 'translation_domain' => 'FOSUserBundle','attr'=>  array(
              		'class'   => 'userForm')
              ))
            ->add('adPhone','text',array(
			 'label' => 'form.adPhone', 'translation_domain' => 'FOSUserBundle','attr'=>  array(
              		'class'   => 'userForm')
              ))
            ->add('adAddress','text',array(
			 'label' => 'form.adAddress', 'translation_domain' => 'FOSUserBundle','attr'=>  array(
              		'class'   => 'userForm')
              ))
            ->add('adAttentionHoursInit','choice', array(
                  'choices' =>$this->getHours(),
                  'multiple' => false,'label' => 'form.adAttentionHoursInit', 'translation_domain' => 'FOSUserBundle')
              )
             ->add('adAttentionHoursFinish','choice', array(
              		'choices' =>$this->getHours(),
              		 'multiple' => false,'label' => 'form.adAttentionHoursFinish', 'translation_domain' => 'FOSUserBundle')
             )
             ->add('adAttentiondaysInit','choice', array(
              		'choices' =>$this->getDays(),
 					'multiple' => false,'label' => 'form.adAttentiondaysInit', 'translation_domain' => 'FOSUserBundle')
              
              )
             ->add('adAttentiondaysFinish','choice', array(
              		'choices' =>$this->getDays(),
 					'multiple' => false,'label' => 'form.adAttentiondaysFinish', 'translation_domain' => 'FOSUserBundle')
                )
            ->add('adLatitude','text',array(
			 'label' => 'form.adLatitude', 'translation_domain' => 'FOSUserBundle','attr'=>  array(
              		'class'   => 'userForm','readonly'   => '','required'=>'')
              ))
            ->add('adLongitude','text',array(
			 'label' => 'form.adLongitude', 'translation_domain' => 'FOSUserBundle','attr'=>  array(
              		'class'   => 'userForm','readonly'   => '')
              ))
              ->add('Guardar','submit',array(
			 'attr'=>  array(
              		'class'   => 'button success')
              ))
              ;
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerAds'
        ));
    }

    public function getHours(){
    	return  array(
                      '7:00'   =>  '7:00  Am',
                      '8:00'   =>  '8:00  Am',
                      '9:00'   =>  '9:00  Am',
                      '10:00'   => '10:00 Am',
                      '11:00'   => '11:00 Am',
                      '12:00'   => '12:00 Pm',
                      '13:00'   => '1:00  Pm',
                  	  '14:00'   => '2:00  Pm',
                  	  '15:00'   => '3:00  Pm',
                  	  '16:00'   => '4:00  Pm',
                  	  '17:00'   => '5:00  Pm',
                  	  '18:00'   => '6:00  Pm',
                  	  '19:00'   => '7:00  Pm',
                  	  '20:00'   => '8:00  Pm',
                  	  '21:00'   => '9:00  Pm',
                  	  '22:00'   => '10:00 Pm',
                  	  '23:00'   => '11:00 Pm',
                  	  '24:00'   => '12:00 Pm',
                  	  '1:00'   =>  '1:00  Am',
                  	  '2:00'   =>  '2:00  Am',
                  	  '3:00'   =>  '3:00  Am',
                  	  '4:00'   =>  '4:00  Am',
                  	  '5:00'   =>  '5:00  Am',
                  	  '6:00'   =>  '6:00  Am',
                  );
    }
   
    public function getDays(){
    	return  array(
    			'Lunes'   =>  'Lunes',
    			'Martes'   =>  'Martes',
    			'Miercoles'   =>  'Miercoles',
    			'Jueves'   => 'Jueves',
    			'Viernes'   => 'Viernes',
    			'Sabado'   => 'Sabado',
    			'Domingo'   => 'Domingo',
    	);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'seekerplus_adsmanagerbundle_adsmanagerads';
    }
}
