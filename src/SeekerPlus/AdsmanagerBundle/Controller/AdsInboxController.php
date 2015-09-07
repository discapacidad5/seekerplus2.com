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
use \DateTime;
use \DateInterval;
use Symfony\Component\HttpFoundation\File\File;
use SeekerPlus\AdsmanagerBundle\Entity\AdsmanagerCities;

class AdsInboxController extends Controller
{
   



public function emailAdsAction(Request $request){

  
        if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirectToRoute('fos_user_security_login');
        }


            $request = $this->container->get('request');
            $Subject= json_decode($request->request->get('Subject'));
            $Messaje= json_decode($request->request->get('Message'));
            $idAd = json_decode($request->request->get('idAd'));
       
   
            $userId = $this->get('security.context')->getToken()->getUser()->getId();
            $emUser = $this->getDoctrine()->getEntityManager();
            $usuario = $emUser->getRepository('AdsmanagerBundle:AdsmanagerAds')->find( $idAd);
            //Usuario de Anuncio destino
            $emailUsuarioAds = $emUser->getRepository('UserBundle:User')->find($usuario->getUserid());
            //Usuario actual origen
            $emailUsuarioContact = $emUser->getRepository('UserBundle:User')->find($userId);


 //Message DB           
             $inInbox=new AdsInbox();  
             $dateTime = new \DateTime();
             $date = $dateTime->format('d/m/y H:i:s');
            
             $em = $this->getDoctrine()->getManager();
             $inInbox->setIdUserOrigin($em->getReference('AdsmanagerBundle:AdsUsers', $userId));
             $inInbox->setIdUserDestination($em->getReference('AdsmanagerBundle:AdsUsers',$usuario->getUserid()));
             $inInbox->setSubjectInbox($Subject);
             $inInbox->setMessajeInbox($Messaje);
             $inInbox->setState("Enviado");
             $inInbox->setState2(0);
             $inInbox->setSee1(0);
             $inInbox->setSee2(0);
             $inInbox->setDateCreated( $dateTime);

             $inInbox->setIdAds($em->getReference('AdsmanagerBundle:AdsmanagerAds', $idAd));
             $em->persist($inInbox);
             $em->flush();


///Message
             $message = \Swift_Message::newInstance()
            ->setSubject($Subject)
            ->setFrom($emailUsuarioAds->getEmail())
            ->setTo($emailUsuarioAds->getEmail())
            ->setBody(
             $this->renderView(
             'AdsmanagerBundle:Inbox:email.html.twig',
             array('Subject' =>  $Subject ,'Messaje' => $Messaje,"Name" => $emailUsuarioAds->getName()
             ,"nameOrigin" =>  $emailUsuarioContact->getName() ,"emailOrigin" => $emailUsuarioContact->getEmail()      
                  ,"Company"=> $usuario->getAdHeadline() )
                    
                )
            ,'text / html');
            $this->get('mailer')->send($message);


        
        $response = array("menssage" =>   $Messaje,"subject" =>   $Subject ,"ida" => $usuario->getUserid());
        return new JsonResponse($response);

 }

public function inboxEmailAdsAction(){

         if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirectToRoute('fos_user_security_login');
}
       return $this->render('AdsmanagerBundle:Inbox:inbox.html.twig');

}



public function inboxOpenInboxMessageAction(Request $request){

  if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirectToRoute('fos_user_security_login');
  }
  $inbox = $this->getDoctrine()->getEntityManager();  
  $request = $this->container->get('request');
  $id= json_decode($request->request->get('id'));
  $action= json_decode($request->request->get('action'));
 
  $infoMessage =  $inbox->getRepository('AdsmanagerBundle:AdsInbox')->find($id);

  $originUser = $inbox->getRepository('UserBundle:User')->find($infoMessage->getIdUserOrigin());
  $userDestination = $inbox->getRepository('UserBundle:User')->find($infoMessage->getIdUserDestination());
  if ($action == 2) {



         return $this->render('AdsmanagerBundle:Inbox:email-opened.html.twig',
         array('originUser'=> $originUser,'infoMessage' => $infoMessage,
         'userDestination' => $userDestination));
   

  }else{

            if ($infoMessage->getSee1() == 0) {
                   $infoMessage ->setSee1(1);
                   $inbox ->flush();
            }
      
       return $this->render('AdsmanagerBundle:Inbox:email-opened-inbox.html.twig',
          array('originUser'=> $originUser,'infoMessage' => $infoMessage,
          'userDestination' => $userDestination));

  }

}



public function inboxSentAction(){
    if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
              return $this->redirectToRoute('fos_user_security_login');
    }
    $userId = $this->get('security.context')->getToken()->getUser()->getId();
    
       $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(
           
        'SELECT a
            FROM AdsmanagerBundle:AdsInbox a
            INNER JOIN a.idUserOrigin c
            INNER JOIN AdsmanagerBundle:AdsUsers b
            WHERE a.idUserOrigin = :id 
            and  a.state = :status
            or  a.idUserOrigin = :id
            and  a.state = :status2

        
            ORDER BY a.id DESC  
         '
         )->setParameter('id',  $userId)->setParameter('status', "Enviado")->setParameter('status2', "Respuesta");
         $listMessages= $query->getResult();

    return $this->render('AdsmanagerBundle:Inbox:email-sent.html.twig'
        ,array('list' => $listMessages));

}

public function inboxListAction(){
    if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
              return $this->redirectToRoute('fos_user_security_login');
    }

    $userId = $this->get('security.context')->getToken()->getUser()->getId();

       $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(

           'SELECT a
            FROM AdsmanagerBundle:AdsInbox a
            INNER JOIN a.idUserDestination  c
            INNER JOIN AdsmanagerBundle:AdsUsers b
            WHERE a.idUserDestination = :id 
            and  a.idUserDestination  = c.id
            and  a.state2 = :status2
                or  a.idUserDestination = :id
            and  a.state2 = :status2

            ORDER BY a.id DESC  
         '
    
         )->setParameter('id',  $userId)->setParameter('status2', "Respuesta");
         $listMessages= $query->getResult();
 
 return $this->render('AdsmanagerBundle:Inbox:email-list.html.twig'
        ,array('list' => $listMessages));
}


public function responseMessageAction(Request $request){

     if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirectToRoute('fos_user_security_login');
  }
  $inbox = $this->getDoctrine()->getEntityManager();  
  $request = $this->container->get('request');
  $id= json_decode($request->request->get('id'));
  $idAd= json_decode($request->request->get('idAd'));

  $userDestination = $inbox->getRepository('UserBundle:User')->find($id);
  
  return $this->render('AdsmanagerBundle:Inbox:email-compose.html.twig',
         array('userDestination' => $userDestination,"idAd" => $idAd));
}

public function saveMessageAction(Request $request){

    if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirectToRoute('fos_user_security_login');
  }
 

  $request = $this->container->get('request');
  $id= json_decode($request->request->get('id'));
  $userId = $this->get('security.context')->getToken()->getUser()->getId();
  $message= json_decode($request->request->get('message'));
  $subject= json_decode($request->request->get('subject'));
  $idAd= json_decode($request->request->get('idAd'));
  $idUser= json_decode($request->request->get('idUser'));
  $action= json_decode($request->request->get('action'));

  $emUser = $this->getDoctrine()->getEntityManager();
  $usuario= $emUser->getRepository('UserBundle:User')->find($idUser);
  $usuarioOrigin= $emUser->getRepository('UserBundle:User')->find($userId );
  $nameAds = $emUser->getRepository('AdsmanagerBundle:AdsmanagerAds')->find($idAd);

  $inInbox=new AdsInbox();  
  $dateTime = new \DateTime();
  $date = $dateTime->format('d/m/y H:i:s');
  $em = $this->getDoctrine()->getManager();
  $inInbox->setIdUserOrigin($em->getReference('AdsmanagerBundle:AdsUsers',  $userId));
  $inInbox->setIdUserDestination($em->getReference('AdsmanagerBundle:AdsUsers',$idUser));
  $inInbox->setSubjectInbox($subject);
  $inInbox->setMessajeInbox($message);
  if ( $action == 2) {
       $inInbox->setState(0);
       $inInbox->setState2("Respuesta");
       $inInbox->setSee1(0);
       $inInbox->setSee2(0);
  }else{
       $inInbox->setState("Respuesta");
       $inInbox->setState2(0);
       $inInbox->setSee1(0);
       $inInbox->setSee2(0);
  }

  $inInbox->setDateCreated( $dateTime);
  $inInbox->setIdAds($em->getReference('AdsmanagerBundle:AdsmanagerAds',$idAd));
  $em->persist($inInbox);
  $em->flush();

  $Messa = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($usuario->getEmail())
            ->setTo($usuario->getEmail())
            ->setBody(
             $this->renderView(
             'AdsmanagerBundle:Inbox:email.html.twig',
             array('Subject' =>  $subject ,'Messaje' =>  $message,"Name" => $usuarioOrigin->getName()
             ,"nameOrigin" =>  $usuarioOrigin->getName() ,"emailOrigin" => $usuarioOrigin->getEmail()      
                  ,"Company"=>   $usuario->getName() )
                    
                )
            ,'text / html');
            $this->get('mailer')->send($Messa);

   $mes="<strong>"."Mensaje Enviado con Exito ! "."</strong>";
  
   $response = array("mes" => $mes);
        return new JsonResponse($response);
   
}




public function inboxemailAdsRAction($idA){
      if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirectToRoute('fos_user_security_login');
        }
   $userId = $this->get('security.context')->getToken()->getUser()->getId();
   $emUser = $this->getDoctrine()->getEntityManager();

   $usuario = $emUser->getRepository('AdsmanagerBundle:AdsmanagerAds')->find($idA);

   if ($usuario->getUserid()== $userId) {
        return $this->render('AdsmanagerBundle:InboxAds:inbox-Ads.html.twig',
        array("idAd" => $idA ));
   }else{
     return $this->redirectToRoute('my_ads');
   }

      
}




public function message_ListInboxAction(Request $request){

if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
              return $this->redirectToRoute('fos_user_security_login');
    }

    $userId = $this->get('security.context')->getToken()->getUser()->getId();
    $request = $this->container->get('request');
    $idAd= json_decode($request->request->get('idAd'));
       $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(

                'SELECT a
            FROM AdsmanagerBundle:AdsInbox a
            INNER JOIN a.idUserDestination  c
            INNER JOIN AdsmanagerBundle:AdsUsers b
            WHERE a.idAds= :id 
            and  a.state = :status
            or  a.idAds= :id 
            and a.state = :status2
            ORDER BY a.id DESC  
         '
    
    
         )->setParameter('id',    $idAd)->setParameter('status', "Enviado")->setParameter('status2', "Respuesta");
         $listMessages= $query->getResult();
 
 return $this->render('AdsmanagerBundle:InboxAds:email-listAds.html.twig'
        ,array('list' => $listMessages));
}

public function message_SentInboxAction(Request $request){
if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
              return $this->redirectToRoute('fos_user_security_login');
    }
    $userId = $this->get('security.context')->getToken()->getUser()->getId();
    $request = $this->container->get('request');
    $idAd= json_decode($request->request->get('idAd'));

       $em = $this->getDoctrine()->getManager();
         $query = $em->createQuery(

                'SELECT a
            FROM AdsmanagerBundle:AdsInbox a
            INNER JOIN a.idUserDestination  c
            INNER JOIN AdsmanagerBundle:AdsUsers b
            WHERE a.idAds= :id 
            and  a.state2 = :status2
            ORDER BY a.id DESC  
         '
    
    
         )->setParameter('id',   $idAd)->setParameter('status2', "Respuesta");
         $listMessages= $query->getResult();

    return $this->render('AdsmanagerBundle:InboxAds:email-sentAds.html.twig'
        ,array('list' => $listMessages));

}

public function messageOpenInboxAdsAction(Request $request){

  if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirectToRoute('fos_user_security_login');
  }
  $inbox = $this->getDoctrine()->getEntityManager();  
  $request = $this->container->get('request');
  $id= json_decode($request->request->get('id'));
  $action= json_decode($request->request->get('action'));
  $infoMessage =  $inbox->getRepository('AdsmanagerBundle:AdsInbox')->find($id);

  $originUser = $inbox->getRepository('UserBundle:User')->find($infoMessage->getIdUserOrigin());
  $userDestination = $inbox->getRepository('UserBundle:User')->find($infoMessage->getIdUserDestination());
  if ($action == 1 ) {

          if ($infoMessage->getSee2() == 0) {
                   $infoMessage ->setSee2(1);
                   $inbox ->flush();
            }
      

     return $this->render('AdsmanagerBundle:InboxAds:email-opened-inboxAds.html.twig',
         array('originUser'=> $originUser,'infoMessage' => $infoMessage,
         'userDestination' => $userDestination));



  }else {
      return $this->render('AdsmanagerBundle:InboxAds:email-openedAds.html.twig',
         array('originUser'=> $originUser,'infoMessage' => $infoMessage,
         'userDestination' => $userDestination));
  }


}

public function message_ResponseInboxAction(Request $request){

     if(!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->redirectToRoute('fos_user_security_login');
  }
  $inbox = $this->getDoctrine()->getEntityManager();  
  $request = $this->container->get('request');
  $id= json_decode($request->request->get('id'));
  $idAd= json_decode($request->request->get('idAd'));

  $userDestination = $inbox->getRepository('UserBundle:User')->find($id);
  
  return $this->render('AdsmanagerBundle:InboxAds:email-composeAds.html.twig',
         array('userDestination' => $userDestination,"idAd" => $idAd));
 }




}
