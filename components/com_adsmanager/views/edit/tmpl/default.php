<?php
/**
 * @package		AdsManager
 * @copyright	Copyright (C) 2010-2014 Juloa.com. All rights reserved.
 * @license		GNU/GPL
 */
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
if (isset($this->warning_text))
	echo '<div class="alert alert-warning">'.htmlspecialchars($this->warning_text)."</div>";

if (isset($this->error_text))
	echo '<div class="alert alert-warning">'.htmlspecialchars($this->error_text)."</div>";
echo JText::_('ADSMANAGER_RULESREAD');

$app = JFactory::getApplication();

$nbimages = $this->conf->nb_images;
if (PAIDSYSTEM) {
	$nbimages += getMaxPaidSystemImages();
}
if ($nbimages > 0) {
	$withImages = true;
} else {
	$withImages = false;
}
	$without_page_reload = 1;
	if (PAIDSYSTEM) {
		$without_page_reload = @getPaidSystemConfig()->without_page_reload;
	}
	
	$target = TRoute::_("index.php?option=com_adsmanager&task=save"); 
    
    JHtml::_('behavior.framework');
	$document = JFactory::getDocument();
	$document->addScript(JURI::root().'components/com_adsmanager/js/jquery.validate.js');
?>
<div class="container-fluid">
<form action="<?php echo $target;?>" method="post" class="form-horizontal" name="adminForm" id="adminForm" enctype="multipart/form-data" onkeypress="return checkEnter(event)" onsubmit="return submitbutton(this)">
	<!-- titel -->
	<legend>
	<?php
	 if($this->isUpdateMode) {
	   echo JText::_('ADSMANAGER_CONTENT_EDIT');
	 }
	 else {
	   echo JText::_('ADSMANAGER_CONTENT_WRITE');
	 }
	 ?>
	</legend>
	<!-- titel -->
  <!-- form -->
   <!-- category -->
       <fieldset>
        <?php
          $target = TRoute::_("index.php?option=com_adsmanager&task=save"); 
          if ($this->nbcats == 1)
          {
            if ($without_page_reload == 1) {
                    $selectid = "category";
                } else {
                    $selectid = "categoryselect";
                }
            ?>
            <div class="control-group">
                <?php if(@$this->conf->single_category_display_label): ?>
                    <label class="control-label" for="<?php echo $selectid; ?>"><?php echo JText::_('ADSMANAGER_SELECT_CATEGORY_LABEL') ?></label>
                <?php endif; ?>
                <div class="controls">
                <?php
                    switch($this->conf->single_category_selection_type) {
                        default:
                        case 'normal':
                                JHTMLAdsmanagerCategory::displayNormalCategories($selectid ,$this->cats,$this->catid,array("root_allowed"=>$this->conf->root_allowed,"display_price"=>true),array("required"=>"","class"=>"input-large text-center"));break;
                        case 'color':
                                JHTMLAdsmanagerCategory::displayColorCategories($selectid ,$this->cats,$this->catid,array("root_allowed"=>$this->conf->root_allowed,"display_price"=>true),array("required"=>"","class"=>"input-large text-center"));break;
                        case 'combobox':
                                JHTMLAdsmanagerCategory::displayComboboxCategories($selectid ,$this->cats,$this->catid,array("root_allowed"=>$this->conf->root_allowed,"display_price"=>true),array("required"=>"","class"=>"input-large text-center"));break;
                            break;
                        case 'cascade':
                                JHTMLAdsmanagerCategory::displaySplitCategories($selectid ,$this->cats,$this->catid,array("root_allowed"=>$this->conf->root_allowed,"display_price"=>true),array("required"=>"","class"=>"input-large text-center"));break;
                    }
                ?>
                </div>
		<?php if (@$this->content->id) { 
			$write_url = TRoute::_('index.php?option=com_adsmanager&task=write&id='.$this->content->id);
		} else {
			$write_url = TRoute::_('index.php?option=com_adsmanager&task=write');
		}?>
		<script type="text/javascript">
		jQ(document).ready(function() {
			<?php if ($without_page_reload == 1) {?>
			jQ('#category').change(function() {
				updateFields();
                <?php foreach($this->fields as $field) { 
                    if (@$field->options->is_conditional_field == 1) { ?>
                dependency('<?php echo $field->name?>',
                           '<?php echo $field->options->conditional_parent_name?>',
                           '<?php echo $field->options->conditional_parent_value?>');
                    <?php } 
                }?>
			});
			<?php } else { ?>
			jQ('#categoryselect').change(function() {
				if (jQ(this).val() != "") {
					url = "<?php echo $write_url?>";
					if (url.indexOf("?") != -1) {
						location.href = "<?php echo $write_url?>&catid="+jQ(this).val();
					} else {
						location.href = "<?php echo $write_url?>?catid="+jQ(this).val();
					}
				}
			});
			<?php } ?>
		});
		</script>
		<?php if ($this->rootid != 0) {?>
		<input type="hidden" value="<?php echo $this->rootid?>" name="rootid"/>
		<?php } ?>
		<?php
        if(!$without_page_reload) {
            echo "<input type='hidden' name='category' value='$this->catid' />";
        }
		?>
        </div>
		<?php
	  }
	  else
	  {
		?>
   		<div class="control-group">
            <?php
                if (isset($this->content->catsid)) {
                    $catids = $this->content->catsid;
                } else {
                    $catids = array();
                }
                $nbcats = $this->conf->nbcats;
                if (PAIDSYSTEM) {
                    $nbcats = getMaxCats($nbcats);
                }
                JHTMLAdsmanagerCategory::displayMultipleCategories("cats",$this->cats,$catids,array("root_allowed"=>$this->conf->root_allowed,"display_price"=>true),$nbcats);
                ?>
                <?php 
                if (PAIDSYSTEM)
                {
                    displayPaidCat($this->conf->nbcats);
                }
                else
                {
                    echo sprintf(JText::_('ADSMANAGER_NBCATS_LEGEND'),$this->conf->nbcats);
                }
            ?>
        </div>
        <?php 
	  }
	?>
       </fieldset>
	<!-- fields -->
	<?php
	if (($this->nbcats != 1) ||
 		($without_page_reload == 1) ||
		(($this->submit_allow == 1)&&( (!isset($this->catid))||($this->catid != 0) ) )
	   )
	{
		/* Submission_type == 0 -> Account Creation with ad posting */
		if ($this->account_creation == 1)
		{
            //TODO user data custom block
                echo "<fieldset>";
                echo "<div class=\"row-fluid\">";
			echo "<span class=\"help-block\">".JText::_('ADSMANAGER_AUTOMATIC_ACCOUNT')."</span>";
                echo "</div>";
                echo "<div class=\"row-fluid\">";
                echo "<div class=\"span6\">";
                echo "<div class=\"control-group\">";
                echo "<label class=\"control-label inline-control-label\" for=\"username\">".JText::_('ADSMANAGER_UNAME').JText::_('ADSMANAGER_REQUIRED')."</label>";
			if (isset($this->content->username))
			{
				$username = $this->content->username;
				$password = $this->content->password;
				$email = $this->content->email;
				$name = $this->content->name;
				$style = 'style="background-color:#ff0000"';
			}
			else
			{
				$username = "";
				$password = "";
				$email = "";
				$name =  "";
				$style = "";
			}
								
			if (isset($this->content->firstname))
				$firstname = $this->content->firstname;
			else
				$firstname = "";
			
			if (isset($this->content->middlename))
				$middlename = $this->content->middlename;
			else
				$middlename = "";
			
			if (COMMUNITY_BUILDER == 1)
			{
				include_once( JPATH_BASE .'/administrator/components/com_comprofiler/ue_config.php' );
				$namestyle = $ueConfig['name_style'];
			}
			else
				$namestyle = 1;
				
                echo "<div class=\"controls inline-controls\"><input $style required id='username' type='text' name='username' size='20' maxlength='255' value=\"".htmlspecialchars($username)."\" /></div>"; 
                echo "</div>";
				echo "</div>";
            
                echo "<div class=\"span6\">";
            	echo "<div class=\"control-group\">";
                echo "<label class=\"control-label inline-control-label\" for=\"password\">".JText::_('ADSMANAGER_PASSWORD').JText::_('ADSMANAGER_REQUIRED')."</label>";
                echo "<div class=\"controls inline-controls\"><input $style required id='password' type='password' name='password' size='20' maxlength='255' value='' /></div>"; 
                echo "</div>";
                echo "</div>";
				echo "</div>";
            
            $emailField = false;
			$nameField = false;
			foreach($this->fields as $field) 
			{
				if (($field->name == "email")&&((strpos($field->catsid, ",$this->catid,") !== false)||(strpos($field->catsid, ",-1,") !== false)))
				{
					$emailField = true;
					// Force required 
					$field->required = 1;
				}
				else if (($field->name == "name")&&((strpos($field->catsid, ",$this->catid,") !== false)||(strpos($field->catsid, ",-1,") !== false)))
				{
					$nameField = true;
					// Force required 
					$field->required = 1;
				}
				else if (($namestyle >= 2)&&($field->name == "firstname")&&((strpos($field->catsid, ",$this->catid,") !== false)||(strpos($field->catsid, ",-1,") !== false)))
				{
					$firstnameField = true;
					// Force required 
					$field->required = 1;
				}
				else if( ($namestyle == 3)&&($field->name == "middlename")&&((strpos($field->catsid, ",$this->catid,") !== false)||(strpos($field->catsid, ",-1,") !== false)))
				{
					$middlenameField = true;
					// Force required 
					$field->required = 1;
				}			
			}
			if (($namestyle >= 2)&&($firstnameField == false))
			{
                    echo "<div class=\"row-fluid\">";
                    echo "<div class=\"span12\">";
                echo "<div class=\"control-group\">";
                    echo "<label class=\"control-label inline-control-label\" for=\"firstname\">".JText::_('ADSMANAGER_FNAME').JText::_('ADSMANAGER_REQUIRED')."</label>";
                    echo "<div class=\"controls inline-controls\"><input $style required id='firstname' type='text' name='firstname' size='20' maxlength='255' value=\"".htmlspecialchars($firstname)."\" /></div>"; 
                    echo "</div>";
                    echo "</div>";
                echo "</div>";
            }
			if ( ($namestyle == 3)&&($middlenameField == false))
			{
                    echo "<div class=\"row-fluid\">";
                    echo "<div class=\"span12\">";
                	echo "<div class=\"control-group\">";
                    echo "<label class=\"control-label inline-control-label\" for=\"middlename\">".JText::_('ADSMANAGER_MNAME').JText::_('ADSMANAGER_REQUIRED')."</label>";
                    echo "<div class=\"controls inline-controls\"><input $style required id='middlename' type='text' name='middlename' size='20' maxlength='255' value=\"".htmlspecialchars($middlename)."\" /></div>"; 
                    echo "</div>";
                    echo "</div>";
                echo "</div>";
            }
			if ($nameField == false)
			{
                    echo "<div class=\"row-fluid\">";
                    echo "<div class=\"span12\">";
                echo "<div class=\"control-group\">";
                    echo "<label class=\"control-label inline-control-label\" for=\"name\">".JText::_('ADSMANAGER_FORM_NAME').JText::_('ADSMANAGER_REQUIRED')."</label>";
                    echo "<div class=\"controls inline-controls\"><input $style required id='name' type='text' name='name' size='20' maxlength='255' value=\"".htmlspecialchars($name)."\" /></div>"; 
                    echo "</div>";   
                    echo "</div>";
                echo "</div>";                
            }
			if ($emailField == false)
			{
                    echo "<div class=\"row-fluid\">";
                    echo "<div class=\"span12\">";
                	echo "<div class=\"control-group\">";
                    echo "<label class=\"control-label inline-control-label\" for=\"email\">".JText::_('ADSMANAGER_FORM_EMAIL').JText::_('ADSMANAGER_REQUIRED')."</label>";
                    echo "<div class=\"controls inline-controls\"><input $style required id='email' type='text' name='email' size='20' maxlength='255' value=\"".htmlspecialchars($email)."\" /></div>"; 
                    echo "</div>";
                echo "</div>";                
            echo "</div>";
        }
                echo "</fieldset>";
            }
		
		/* Display Fields */
        ?>
            <fieldset id="ad-information">
                <legend><?php echo JText::_('ADSMANAGER_FORM_AD_INFORMATION'); ?></legend>
                <div class="row-fluid">
        <?php
		foreach($this->fields as $field)
		{
			if (@$field->options->edit_admin_only == 0) {
				$fieldform = $this->field->showFieldForm($field,$this->content,$this->default);
				if ($fieldform != "") {
                                    echo "<div id=\"row_".$field->name."\" class=\"span12\">";
                    echo "<div class=\"control-group\">";
                    if ($field->required == 1)
                    	$requiredtxt = JText::_('ADSMANAGER_REQUIRED');
                    else
                    	$requiredtxt = "";
                    if ((@$field->description)&&($field->description !="")) {
                    	JHTML::_('behavior.tooltip');
                    	echo "<label class=\"control-label\" for=\"{$field->name}\">".
                    		 JHTML::tooltip(TText::_($field->description),TText::_($field->title),null,$this->field->showFieldLabel($field,$this->content,$this->default).$requiredtxt).
                    		 "</label>";
                    } else {
						echo "<label class=\"control-label\" for=\"{$field->name}\">".$this->field->showFieldLabel($field,$this->content,$this->default).$requiredtxt."</label>";
					}
					echo $fieldform;
                    echo "</div>";
                                    echo "</div>";
				}
			} 
		}
		?>
        </div>
            </fieldset>
		<!-- fields -->
		<!-- image -->
		<?php if ($withImages) {?>
                <fieldset>
                    <legend><?php echo JText::_('ADSMANAGER_FORM_AD_PICTURE'); ?></legend>
                    <div class="row-fluid">
                        <div class="span12">
                <div class="control-group">
                    <div id="uploader_td" class="controls">
                    <?php echo TImage::displayImageUploader($this->conf,$this->content,$this->adext);?>
                    <?php if (PAIDSYSTEM) {
                        displayImagePackOption($this->content,$this->adext);
                    }?>
                    </div>
                </div>
            </div>
                    </div>
                </fieldset>
		<?php } ?>		
		<?php
		if ($this->conf->metadata_mode == 'frontendbackend') {
        ?>
                <fieldset>
                    <legend><?php echo JText::_('ADSMANAGER_FORM_AD_ADDITIONAL_INFORMATION'); ?></legend>
                    <div class="row-fluid">
                        <div class="span12">
                <div class="control-group">
                    <span id='row_metadata' class="help-block"><?php echo JText::_('ADSMANAGER_METADATA'); ?></span>
                    <label class="control-label" for="metadata_description">
                        <?php echo JText::_('ADSMANAGER_METADATA_DESCRIPTION'); ?>
                    </label>
                    <div class="controls">
                        <textarea cols="50" rows="8" id="metadata_description" name="metadata_description"><?php echo htmlspecialchars(@$this->content->metadata_description)?></textarea>			
                    </div>
                </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                <div class="control-group">
                    <label class="control-label" for="metadata_keywords">
                        <?php echo JText::_('ADSMANAGER_METADATA_KEYWORDS'); ?>
                    </label>
                    <div class="controls">
                        <textarea cols="50" rows="8" id="metadata_keywords" name="metadata_keywords"><?php echo htmlspecialchars(@$this->content->metadata_keywords)?></textarea>			
                    </div>
                </div>
            </div>
                    </div>
                </fieldset>
		<?php } ?>
		
		<?php
		
		if (PAIDSYSTEM){
			editPaidAd($this->content,$this->isUpdateMode,$this->conf);
		}
		?>
        <?php
            if($this->isUpdateMode == 0){
                if(isset($this->conf->publication_date) && $this->conf->publication_date == 1) {
        ?>
            <div class="row-fluid">
                <div class="span12">
        <div class="control-group">
            <label class="control-label" for="publication_date">
                <?php echo JText::_('ADSMANAGER_PUBLICATION_DATE').JText::_('ADSMANAGER_REQUIRED'); ?>
            </label>
            <div class="controls">
                <?php
                    if (isset($this->content->publication_date) && $this->content->publication_date != '0000-00-00 00:00:00'){
                        $publication_date = $this->content->publication_date;
                    }else{
                        $publication_date = "";
                    }

                    $options = array();
                    $options['size'] = 25;
                    $options['maxlength'] = 19;
                    $options['required'] = 'required';
                    $options['mosReq'] = '1';
                    $options['mosLabel'] = htmlspecialchars(JText::_('ADSMANAGER_PUBLICATION_DATE'));
                    $return = JHTML::_('behavior.calendar');
                    $return .= JHTML::_('calendar', $publication_date, "publication_date", "publication_date", '%Y-%m-%d', $options);
    //              $return .= "<script type='text/javascript'>jQ(document).ready(function() {jQ('#publication_date').val(".json_encode($publication_date).");});</script>";

                    echo $return;
                ?>
            </div>
		</div>
                </div>
            </div>
        <?php
                }
            }
        ?>
		<?php echo $this->event->onContentAfterForm ?>	
		<!-- buttons -->
		<?php if ((@$this->content->id == 0)&&(@$this->conf->show_accept_rules == 1)) {?>
            <div class="row-fluid">
                <div class="span12">
		<div class="control-group">
            <div class="controls">
                <label class="checkbox">
                    <input type="checkbox" id="acceptrules" name="acceptrules" required value="1" />
                    <a href="<?php echo TRoute::_('index.php?option=com_adsmanager&view=rules')?>" target="_blank" >
                        <?php echo htmlspecialchars(JText::_('ADSMANAGER_ACCEPT_RULES_CHECKBOX'))?>
                    </a>
                </label>
            </div>
        </div>
                </div>
            </div>
		<?php } ?>
        <div class="row-fluid"><div class="span12"><hr/></div></div>
		<div class="row-fluid">
            <div class="span12">
                <input type="button" class="btn" onclick='window.location="<?php echo TRoute::_("index.php?option=com_adsmanager&view=list"); ?>"' value="<?php echo JText::_('ADSMANAGER_FORM_CANCEL_TEXT'); ?>" />
                <?php if(isset($this->conf->preview_ads) && $this->conf->preview_ads != 0): ?>
                <input type="button" class="btn" onclick='submitpreview(adminForm);' value="<?php echo JText::_('ADSMANAGER_FORM_PREVIEW_TEXT'); ?>" />
                <?php endif; ?>
                <input type="submit" class="btn btn-primary" value="<?php echo JText::_('ADSMANAGER_FORM_SUBMIT_TEXT'); ?>" />
            </div>
        </div>
		<!-- buttons -->
	<?php
	}
	?>
  <?php echo JHTML::_( 'form.token' ); ?>

<!-- form -->
<?php
if (isset($this->content->date_created))
	echo "<input type='hidden' name='date_created' value='".$this->content->date_created."' />";	
	
echo "<input type='hidden' name='isUpdateMode' value='".$this->isUpdateMode."' />";
echo "<input type='hidden' name='id' value='".@$this->content->id."' />";
echo "<input type='hidden' name='pending' value='".@$this->content->pending."' />";
echo '<input type="hidden" name="preview" value="0" id="preview" />';
?>
</form>
</div>
<script type="text/javascript">	
function CaracMax(text, max)
{
	if (text.value.length >= max)
	{
		text.value = text.value.substr(0, max - 1) ;
	}
}

function checkEnter(e){
	 e = e || event;
	 if(e.keyCode == 13 && e.target.nodeName!='TEXTAREA')
     {
       e.preventDefault();
       return false;
     }
}

function submitpreview() {
    jQ('#preview').val(1);
    jQ('#adminForm').submit();
}

jQ().ready(function() {
	jQ('#adminForm').validate({
		submitHandler: function(form) {
			if (submitbutton() == true) {
		        form.submit();
		    }
		}
	});
});
	
function submitbutton(mfrm) {

	var errorMSG = '';
	var iserror=0;
	
	<?php 
	if (PAIDSYSTEM){
		loadEditFormCheck();
	}
	?>
	
	<?php if ($this->nbcats > 1)
	{
	?>
		var form = document.adminForm;
		var srcList = eval( 'form.selected_cats' );
		var srcLen = srcList.length;
		if (srcLen == 0)
		{
			errorMSG += <?php echo json_encode(JText::_('ADSMANAGER_FORM_CATEGORY')); ?>+" : "+<?php echo json_encode(JText::_('ADSMANAGER_REGWARN_ERROR')); ?>+'\n';
			srcList.style.background = "#B94A48";
			iserror=1;
		}
		else
		{
			for (var i=0; i < srcLen; i++) {
				srcList.options[i].selected = true;
			}
		}
	<?php
	}
	?>
	
	<?php
	TImage::displayImageUploaderFormChecker();
	?>
	
	if(iserror==1) {
		return false;
	} else {

	        
		//Little hack to be able to return the selected_cats
		<?php if ($this->nbcats > 1) { ?>
			srcList.name = "selected_cats[]"; 
		<?php } ?>
		return true;
	}
}

function updateFields() {
	var form = document.adminForm;
	var singlecat = 0;
	var length = 0;
	
	if ( typeof(document.adminForm.category ) != "undefined" ) {
		singlecat = 1;
		length = 1;
	}
	else if (typeof(document.adminForm.selected_cats ) != "undefined")
	{
		length = form.selected_cats.length;
	} else {
		length = 1;
	}
	
	<?php
	foreach($this->fields as $field)
	{ 
		if (strpos($field->catsid, ",-1,") === false)
		{
			$name = $field->name;
			if (($field->type == "multicheckbox")||($field->type == "multiselect"))
				$name .= "[]";
		?>
		var input = document.getElementById('<?php echo $name;?>');
		var trzone = document.getElementById('row_<?php echo $field->name;?>');
		if (((singlecat == 0)&&(length == 0))||
		    ((singlecat == 1)&&(document.adminForm.category.value == 0)))
		{
			if (input != null)
				input.style.visibility = 'hidden';
			if (trzone != null) {
				trzone.style.visibility = 'hidden';
				trzone.style.display = 'none';
			}
		}
		else
		{
			for (var i=0; i < length; i++) {
				var field_<?php echo $field->name;?> = '<?php echo $field->catsid;?>';
				var temp;
				if (singlecat == 0)
					temp = form.selected_cats.options[i].value;
				else
					temp = document.adminForm.category.value;
					
				var test = field_<?php echo $field->name;?>.indexOf( ","+temp+",", 0 );
				if (test != -1)
				{
					if (input != null)
						input.style.visibility = 'visible';
					if (trzone != null) {
						trzone.style.visibility = 'visible';
						trzone.style.display = '';
					}
					break;
				}
				else
				{
					if (input != null)
						input.style.visibility = 'hidden';
					if (trzone != null) {
						trzone.style.visibility = 'hidden';
						trzone.style.display = 'none';
					}
				}
			}
		}
	<?php
		}
	} 
	?>
}

function checkdependency(child,parentname,parentvalue) {
	//Simple checkbox
	if (jQ('input[name="'+parentname+'"]').is(':checkbox')) {
		//alert("test");
		if (jQ('input[name="'+parentname+'"]').attr('checked')) {
			jQ('#adminForm #'+child).show();
			jQ('#adminForm #row_'+child).show();
		}
		else {
			jQ('#adminForm #'+child).hide();
			jQ('#adminForm #row_'+child).hide();
			
			//cleanup child field 
			if (jQ('#adminForm #'+child).is(':checkbox') || jQ('#adminForm #'+child).is(':radio')) {
				jQ('#adminForm #'+child).attr('checked', false);
			}
			else {
				jQ('#adminForm #'+child).val('');
			}
		} 
	}
	//If checkboxes or radio buttons, special treatment
	else if (jQ('input[name="'+parentname+'"]').is(':radio')  || jQ('input[name="'+parentname+'[]"]').is(':checkbox')) {
		var find = false;
		var allVals = [];
		jQ("input:checked").each(function() {
			if (jQ(this).val() == parentvalue) {	
				jQ('#adminForm #'+child).show();
				jQ('#adminForm #row_'+child).show();
				find = true;
			}
		});
		
		if (find == false) {
			jQ('#adminForm #'+child).hide();
			jQ('#adminForm #row_'+child).hide();
			
			//cleanup child field 
			if (jQ('#adminForm #'+child).is(':checkbox') || jQ('#adminForm #'+child).is(':radio')) {
				jQ('#adminForm #'+child).attr('checked', false);
			}
			else {
				jQ('#adminForm #'+child).val('');
			}
		}

	}
	//simple text
	else if (jQ('#adminForm #'+parentname).val() == parentvalue) {
		jQ('#adminForm #'+child).show();
		jQ('#adminForm #row_'+child).show();
	} 
	else {
		jQ('#adminForm #'+child).hide();
		jQ('#adminForm #row_'+child).hide();
		
		//cleanup child field 
		if (jQ('#adminForm #'+child).is(':checkbox') || jQ('#adminForm #'+child).is(':radio')) {
			jQ('#adminForm #'+child).attr('checked', false);
		}
		else {
			jQ('#adminForm #'+child).val('');
		}
	}
}
function dependency(child,parentname,parentvalue) {
	//if checkboxes
	jQ('input[name="'+parentname+'[]"]').change(function() {
		checkdependency(child,parentname,parentvalue);
	});
	//if buttons radio
	jQ('input[name="'+parentname+'"]').change(function() {
		checkdependency(child,parentname,parentvalue);
	});
	jQ('#'+parentname).click(function() {
		checkdependency(child,parentname,parentvalue);
	});
	checkdependency(child,parentname,parentvalue);
}

jQ(document).ready(function() {
	updateFields();

	<?php foreach($this->fields as $field) { 
		if (@$field->options->is_conditional_field == 1) { ?>
	dependency('<?php echo $field->name?>',
			   '<?php echo $field->options->conditional_parent_name?>',
			   '<?php echo $field->options->conditional_parent_value?>');
		<?php } 
	}?>
});
</script>