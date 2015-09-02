<?php

namespace SeekerPlus\AdsmanagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdsmanagerProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text',array(
			 'label' => 'form.adProductName', 'translation_domain' => 'FOSUserBundle','attr'=>  array(
              		'class'   => 'userForm')
              ))
            ->add('description','textarea',array(
			 'label' => 'form.adProductDescription', 'translation_domain' => 'FOSUserBundle','attr'=>  array(
              		'class'   => 'userForm')
              ))
            ->add('Guardar','submit',array(
            		'attr'=>  array(
            				'class'   => 'button success')
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerProduct'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'seekerplus_adsmanagerbundle_adsmanagerproduct';
    }
}
