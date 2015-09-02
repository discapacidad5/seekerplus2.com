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

$moduleStyle = $params->get('themes','style1');

$document = JFactory::getDocument();
$document->addStyleSheet('modules/mod_jvcounter/assets/styles/'.$moduleStyle.'/default.css');
?>

<div class="jvcounter_contain jvcounter_<?php echo $moduleStyle;?>">
    <?php if($headertext = $params->get('headertext')){?>
        <div class="jvcounter_rows jvcounter_headertext">
            <?php
                echo $headertext;
            ?>
        </div>
    <?php }?>
    
    <?php echo $totalImage;?>
    
    <?php if($params->get('showip',1)){?>
        <div class="jvcounter_rows jvcounter_today">
            <?php
                $ip = $_SERVER['REMOTE_ADDR'] == '::1'?'local':$_SERVER['REMOTE_ADDR']; 
                echo JText::_('Your IP ') . $ip;
            ?>
        </div>
    <?php }?>
    
    <?php if($params->get('showdatetoday',1)){?>
        <div class="jvcounter_rows jvcounter_datetoday">
            <?php 
                $dateformat = $params->get('datetodayformat','%Y-%m-%d %H:%M:%S');
                $timeoffset = time() + 60*60*(int)$params->get('timeoffset',7);
                echo JFactory::getDate($timeoffset)->toFormat($dateformat);
            ?>
        </div>
    <?php }?>
    
    
    
    <?php if($visits['online']){?>
        <div class="jvcounter_rows jvcounter_today">
            <?php echo $params->get('textonline','Online').' '. count($visits['online']);?><br/>
            <?php echo JText::_('User online').' '. count($visits['online']['user']);?><br/>
            <?php echo JText::_('Guest online').' '. count($visits['online']['guest']);?><br/>
        </div>
    <?php }?>
    
    <?php if($visits['today']){?>
        <div class="jvcounter_rows jvcounter_today">
            <?php echo $params->get('texttoday','Today').' '. count($visits['today']);?>
        </div>
    <?php }?>
    
    <?php if($visits['yesterday']){?>
        <div class="jvcounter_rows jvcounter_yesterday">
            <?php echo $params->get('textyesterday','Yesterday').' '. count($visits['yesterday']);?>
        </div>
    <?php }?>
    
    <?php if($visits['thisweek']){?>
        <div class="jvcounter_rows jvcounter_thisweek">
            <?php echo $params->get('textthisweek','This week').' '. count($visits['thisweek']);?>
        </div>
    <?php }?>
    
    <?php if($visits['lastweek']){?>
        <div class="jvcounter_rows jvcounter_lastweek">
            <?php echo $params->get('textlastweek','Last week').' '. count($visits['lastweek']);?>
        </div>
    <?php }?>
    
    <?php if($visits['thismonth']){?>
        <div class="jvcounter_rows jvcounter_thismonth">
            <?php echo $params->get('textthismonth','This month').' '. count($visits['thismonth']);?>
        </div>
    <?php }?>
    
    <?php if($visits['lastmonth']){?>
        <div class="jvcounter_rows jvcounter_lastmonth">
            <?php echo $params->get('textlastmonth','Last month').' '. count($visits['lastmonth']);?>
        </div>
    <?php }?>
    
    <?php if($params->get('showalldays',1)){?>
        <div class="jvcounter_rows jvcounter_alldays">
            <?php echo $params->get('textalldays','All days').' '. $visits['total'] ;?>
        </div>
    <?php }?>
    
    
</div>