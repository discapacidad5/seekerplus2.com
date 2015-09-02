<?php
/**
 # plg_jvcounter
 # @version		1.x.1.0
 # ------------------------------------------------------------------------
 # author    Open Source Code Solutions Co
 # copyright Copyright (C) 2011 joomlavi.com. All Rights Reserved.
 # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL or later.
 # Websites: http://www.joomlavi.com
 # Technical Support:  http://www.joomlavi.com/my-tickets.html
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.plugin.plugin' );

class plgSystemJVCounter extends JPlugin{
    
    function onAfterDispatch(){
        jimport('joomla.html.parameter');
        
        
        $mainframe = JFactory::getApplication();
        if($mainframe->isAdmin()){
            return;
        }
        $this->_db = JFactory::getDbo();
        
        $this->fixInstallJ15();
        
        $this->_params = $this->getParams();
        
        
        $this->_sessions = $this->getSession();
        $this->saveData();
        
        return;
    }
	
	function fixInstallJ15(){
	    $version = new JVersion;
        $joomla = $version->getShortVersion();
        
        if(substr($joomla,0,3) == '1.5'){
           $db = $this->_db;
    		$query = "
    				CREATE TABLE IF NOT EXISTS #__jvcounter_logs (
    				  session_id varchar(255) NOT NULL,
    				  user_id int(11) NOT NULL,
    				  ip varchar(255) NOT NULL,
    				  timestart int(11) NOT NULL,
    				  timelast int(11) NOT NULL,
    				  counter int(11) NOT NULL,
    				  browser text NOT NULL,
    				  timezone varchar(255) NOT NULL,
    				  lasturl text NOT NULL,
    				  PRIMARY KEY (session_id)
    				) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
    		$db->setQuery($query);
    		
    		return $db->query();
        }
        
        return;
		
	}
    
    
    function saveData(){
        $db = $this->_db;
        $data = $this->getDataLogs();
        
        if($sessions = $this->_sessions) foreach($sessions as $key=>$session){
            
            $sessionData = $this->parseSessionData($session->data);
            
            
            $session_id     = $session->session_id;
            $user_id        = $session->userid;
            $timelast       = $session->time;
            $counter        = (int)$sessionData['session.counter'];
            $lasturl        = '';
            
            
            if(isset($data[$key])){
                //update logs
                if($data[$key]->timelast < $session->time){
                    //update
                    $queries[]="UPDATE #__jvcounter_logs
                              SET user_id   = '$user_id', 
                                  timelast  = '$timelast',
                                  counter   = '$counter',
                                  lasturl   = '$lasturl'
                              WHERE session_id = '$key'";
                }
                
            }else{
                //insert new  
                
                $ip             = $this->getRemoteIP();
                $timestart      = $sessionData['session.timer.start']?$sessionData['session.timer.start']:$timelast;
                
                $timezone       = $sessionData['user']?$sessionData['user']->get('_params')->get('timezone'):'';
                $browser        = $sessionData['session.client.browser']?$sessionData['session.client.browser']:$_SERVER['HTTP_USER_AGENT'];
                $queries[]="INSERT INTO #__jvcounter_logs (session_id,user_id,ip,timestart,timelast,counter,browser,timezone,lasturl) VALUES('$session_id','$user_id','$ip','$timestart','$timelast','$counter','$browser','$timezone','$lasturl')";
                
            }
            
        }
        
        if($queries) foreach($queries as $query){
            $db->setQuery($query);
            if(!$db->query()){
                JError::raiseError(500,$db->getErrorMsg());
            }
        }
       
        return;
    }
    
    function getDataLogs(){
        $db = $this->_db;
        $query = "select * from #__jvcounter_logs";
        $db->setQuery($query);
        return $db->loadObjectList('session_id');
    }
    
    function getParams(){
        
        $plugin = & JPluginHelper::getPlugin('system', 'jvcounter');
        $params = new JParameter($plugin->params);
        
        return $params;
    }
    function getSession(){
	   $db = $this->_db;
       $query = "select * from #__session where client_id=0"; //site
       $db->setQuery($query);
       $items = $db->loadObjectList('session_id');
       
       return $items;
	}
    function parseSessionData($data){
        $tmp = explode('|',$data);
        return unserialize($tmp[1]);
        
    }
    function getRemoteIP(){
        
        $ip = $_SERVER['REMOTE_ADDR'];
        if($ip=='::1'){
            $ip = 'local';
        }
        return $ip;
    }
    
    
    
}

?>