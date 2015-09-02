<?php
namespace SeekerPlus\AdsmanagerBundle\Models;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use SeekerPlus\AdsmanagerBundle\Models\Document;

/**
 * Manipula los mensajes hacia el usuario
 *
 * @author Gianci15
 */
class Formdata {

    /**
    * @return true si al formulario se le han enviado datos
    * @return false si al formulario no se le han enviado datos
    */
    public function isSetFormat($request){
           if($request->getMethod() == 'POST')
                return true;
            return false;
      }

    /**
    * @return true si el formularios es valido
    * @return false si el formularios no es valido
    */
    public function isValidFormat($request,$form,$message){
           $form->handleRequest($request);

           if($form->isValid())
           {
              return true;
           }else
           {
              $message->setErrorMessages("".$message->getErrorMessagesForm($form));
              return false;
           }

      }


    /**
    * Valida la imagen
    *
    */
    public function isValidImage($image,$form,$object,$message,$title){

      if (($image instanceof UploadedFile) && ($image->getError() == '0'))
      {
            $originalName = $image->getClientOriginalName();
            $name_array = explode('.', $originalName);
            $file_type = $name_array[sizeof($name_array) - 1];
            $valid_filetypes = array('jpg', 'jpeg','png');
            if(in_array(strtolower($file_type), $valid_filetypes))
             {
                $nombreImagen = $form->get($title)->getData();
                $object->setImages($nombreImagen.'.'.$file_type);
                return true;
            }
            else
            {
                $message->setErrorMessages("El formato de la imagen no es valida");
                return false;

            }
        }else {
                $object->setImages("[]");
                return true;
       }
    }
    
    public function isValidImageUpdate($image,$form,$object,$message,$title){
    
    	if (($image instanceof UploadedFile) && ($image->getError() == '0'))
    	{
    		$originalName = $image->getClientOriginalName();
    		$name_array = explode('.', $originalName);
    		$file_type = $name_array[sizeof($name_array) - 1];
    		$valid_filetypes = array('jpg', 'jpeg','png');
    		if(in_array(strtolower($file_type), $valid_filetypes))
    		{
    			$nombreImagen = $form->get($title)->getData();
    			$object->setImages($nombreImagen.'.'.$file_type);
    			return true;
    		}
    		else
    		{
    			$message->setErrorMessages("El formato de la imagen no es valida");
    			return false;
    
    		}
    	}else {
    		return true;
    	}
    }
    
    /**
    * Valida la imagen que ya existe
    *
    */
    public function isValidImageExist($image,$form,$object,$message){

      if (($image instanceof UploadedFile) && ($image->getError() == '0'))
      {
            $originalName = $image->getClientOriginalName();
            $name_array = explode('.', $originalName);
            $file_type = $name_array[sizeof($name_array) - 1];
            $valid_filetypes = array('jpg', 'jpeg','png');
            if(in_array(strtolower($file_type), $valid_filetypes))
             {
                $nombreImagen = $form->get('nombre')->getData();
                $object->setImages($nombreImagen.'.'.$file_type);
                return true;
            }
            else
            {
                $message->setErrorMessages("El formato de la imagen no es valida");
                return false;

            }
        }else {
                return true;
       }
    }
    /**
    * Ingresar un Registro Nuevo
    *
    */
    public function insertData($class,$object){
         $em=$class->getDoctrine()->getManager();
         $em->persist($object);
         $em->flush();

    }

    /**
    * Actualiza un Registro
    *
    */
    public function updateData($class){
         $em=$class->getDoctrine()->getManager();
         $em->flush();

    }

    /**
    * Elimina un Registro
    *
    */
    public function deleteData($class,$object){
         $em=$class->getDoctrine()->getManager();
         $em->remove($object);
         $em->flush();

    }
    /**
    * Sube la imagen
    *
    */
    public function uploadImages($image,$directory,$object){
      if($image){
              $document = new Document();
              $document->setFile($image);
              $document->setSubDirectory($directory);
              $document->processFile($object->getImages());
      }
    }
    
}
