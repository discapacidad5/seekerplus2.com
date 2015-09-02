<?php
/**
 # mod_jvcounter - Module JV Counter
 # @version		1.x.1
 # ------------------------------------------------------------------------
 # author    Open Source Code Solutions Co
 # copyright Copyright (C) 2011 joomlavi.com. All Rights Reserved.
 # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL or later.
 # Websites: http://www.joomlavi.com
 # Technical Support:  http://www.joomlavi.com/my-tickets.html
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(class_exists('plgSystemJVCounter')){
    require_once dirname(__FILE__).'/helper.php';

    $visits = modJVCounterHelper::getVisits($params);
    $totalImage = modJVCounterHelper::getTotalImage($params,(int)$visits['total']);
    
    $template = $params->get('template','default');
    require JModuleHelper::getLayoutPath('mod_jvcounter',$template);
}else{
    echo 'Please install plugin JVCounter!';
}

?>