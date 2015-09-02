<?php
namespace SeekerPlus\AdsmanagerBundle\Models;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\DBAL\Types\ObjectType;

/**
 * Manipula los mensajes hacia el usuario
 *
 * @author Gianci15
 */
class Message extends Controller{
    /**
     * @var string
     *
     * Muestra los mensajes de errores del Processo
     */
    private $errorMessages="";
    /**
     * @var string
     *
     * Muestra los mensajes de exito del Processo
     */
    private $successMessages="";
    /**
     * @var string
     *
     * Muestra los mensajes de Informacion del Processo
     */
    private $informationMessages="";
    /**
     * @var string
     *
     * Muestra los mensajes de Peligro del Processo
     */
    private $warningMessages="";
    /**
     * @param string $form
     * @return el ultimo error presentado en el formulario
     */

    public function getErrorMessagesForm(\Symfony\Component\Form\Form $form) {
    $errors= array();

    if ($form->count() > 0) {
        foreach ($form->all() as $child) {
            /**
             * @var \Symfony\Component\Form\Form $child
             */
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessagesForm($child);
            }
        }
    } else {
        /**
         * @var \Symfony\Component\Form\FormError $error
         */
        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
            return array_values($errors);
        }

    }
		return "";
    }

  
    public function show($class){

    	if($this->getSuccessMessages()!="")
    		$class->addFlash('success',$this->getSuccessMessages());
    	if($this->getErrorMessages()!="")
    		$class->addFlash('error',$this->getErrorMessages());
    	if($this->getInformationMessages()!="")
    		$class->addFlash('information',$this->getInformationMessages());
    	if($this->getWarningMessages()!="")
    		$class->addFlash('warning',$this->getWarningMessages());
    }

      public function setErrorMessages($errors)
    {
        $this->errorMessages = $errors;
        return $this;
    }

    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

      public function setSuccessMessages($success)
    {
        $this->successMessages = $success;
        return $this;
    }

    public function getSuccessMessages()
    {
        return $this->successMessages;
    }
	public function getInformationMessages() {
		return $this->informationMessages;
	}
	public function setInformationMessages($informationMessages) {
		$this->informationMessages = $informationMessages;
		return $this;
	}
	public function getWarningMessages() {
		return $this->warningMessages;
	}
	public function setWarningMessages($warningMessages) {
		$this->warningMessages = $warningMessages;
		return $this;
	}
	
	
}
