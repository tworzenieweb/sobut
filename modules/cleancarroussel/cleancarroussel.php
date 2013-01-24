<?php
/*
*  @author Fabio Zaffani <fabiozaffani@gmail.com>
*  @version  Release: $Revision: 1.0 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_CAN_LOAD_FILES_'))
	exit;

class cleancarroussel extends Module
{
	/** @var max image size */
	protected $maxImageSize = 307200;

	public function __construct()
	{
		$this->name = 'cleancarroussel';
		$this->tab = 'front_office_features';
		$this->version = '1.1';
		$this->author = 'iLet Develop Team';
		$this->need_instance = 0;
				
		parent::__construct();

		$this->displayName = $this->l('Clean iLet jQuery Carousel');
		$this->description = $this->l('A very clean, fast and easily customizable Carousel made on top of jQuery');
	}

	function install()
	{
		if (
			!parent::install() OR
			!$this->registerHook('header') OR
			!$this->registerHook('footer') OR
			!$this->registerHook('home') OR
			!$this->registerHook('top') OR
			!Configuration::updateValue('WHERE_TO_HOOK', 'home') OR
			!Configuration::updateValue('CLEAN_CAR_WIDTH', '556') OR
			!Configuration::updateValue('CLEAN_CAR_HEIGHT', '220') OR
			!Configuration::updateValue('CLEAN_CHANGE_SPEED', 4000) OR
			!Configuration::updateValue('SHARE_CAR', 1)
		)
			return false;
		return true;
	}

	public function uninstall()
	{
	 	if (
			!parent::uninstall() OR
			!Configuration::deleteByName('WHERE_TO_HOOK') OR
			!Configuration::deleteByName('CLEAN_CAR_WIDTH') OR
			!Configuration::deleteByName('CLEAN_CHANGE_SPEED') OR
			!Configuration::deleteByName('CLEAN_CAR_HEIGHT')
		)
	 		return false;
	 	return true;
	}
	public function putContent($xml_data, $key, $field, $forbidden, $section)
	{
		foreach ($forbidden AS $line)
			if ($key == $line)
				return 0;
		if (!preg_match('/^'.$section.'_/i', $key))
			return 0;
		$key = preg_replace('/^'.$section.'_/i', '', $key);
		$field = htmlspecialchars($field);
		if (!$field)
			return 0;
		return ("\n".'		<'.$key.'>'.$field.'</'.$key.'>');
	}

	public function getContent()
	{	
		/* display the module name */
		$this->_html = '<h2>'.$this->displayName.'</h2>';
		$errors = '';
		
		$TotalDestaques = 5;
		
		for ($i=0;$i<$TotalDestaques;$i++)
		{
			// Delete image
			if (Tools::isSubmit('deleteImage'.$i))
			{
				unlink(dirname(__FILE__).'/carroussel_'.$i.'.jpg');
				$this->_html .= $errors;
			}
		}

		/* update the destaques xml */
		if (isset($_POST['submitUpdate']))
		{
			Configuration::updateValue('WHERE_TO_HOOK', Tools::getValue('where_hook'));
			Configuration::updateValue('CLEAN_CAR_WIDTH', Tools::getValue('carroussel_width'));
			Configuration::updateValue('CLEAN_CAR_HEIGHT', Tools::getValue('carroussel_height'));
			Configuration::updateValue('CLEAN_CHANGE_SPEED', Tools::getValue('carroussel_speed'));

			$newXml = '<?xml version=\'1.0\' encoding=\'utf-8\' ?>'."\n";
			$newXml .= '<destaques>'."\n";
			$newXml .= '	<header>';

			foreach ($_POST AS $key => $field)
				if ($line = $this->putContent($newXml, $key, $field, $forbidden, 'header'))
					$newXml .= $line;
			$newXml .= "\n".'	</header>'."\n";
			$newXml .= '	<body>';

			foreach ($_POST AS $key => $field)
				if ($line = $this->putContent($newXml, $key, $field, $forbidden, 'body'))
					$newXml .= $line;
			$newXml .= "\n".'	</body>'."\n";
			$newXml .= '</destaques>'."\n";

			/* write it into the destaques xml file */
			if ($fd = @fopen(dirname(__FILE__).'/cleancarroussel.xml', 'w'))
			{
				if (!@fwrite($fd, $newXml))
					$errors .= $this->displayError($this->l('Unable to write to the editor file.'));
				if (!@fclose($fd))
					$errors .= $this->displayError($this->l('Can\'t close the editor file.'));
			}
			else
				$errors .= $this->displayError($this->l('Unable to update the editor file.<br />Please check the editor file\'s writing permissions.'));

			$totalDestaques = 5;
			
			for ($i=0; $i<=$totalDestaques; $i++){
				/* upload the image  i */
				if (isset($_FILES['body_carroussel_'.$i]) AND isset($_FILES['body_carroussel_'.$i]['tmp_name']) AND !empty($_FILES['body_carroussel_'.$i]['tmp_name']))
				{
					Configuration::set('PS_IMAGE_GENERATION_METHOD', 1);
					if ($error = checkImage($_FILES['body_carroussel_'.$i], $this->maxImageSize))
						$errors .= $error;
					elseif (!$tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS') OR !move_uploaded_file($_FILES['body_carroussel_'.$i]['tmp_name'], $tmpName))
						return false;
					elseif (!imageResize($tmpName, dirname(__FILE__).'/carroussel_'.$i.'.jpg'))
						$errors .= $this->displayError($this->l('An error occurred during the image upload.'));
					unlink($tmpName);
				}
			}
			$this->_html .= $errors == '' ? $this->displayConfirmation('Settings updated successfully') : $errors;
		}

		/* display the destaques form */
		$this->_displayForm();
	
		return $this->_html;
	}
	private function _displayForm()
	{
		global $cookie;
		/* Languages preliminaries */
		$defaultLanguage = intval(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages();
		$iso = Language::getIsoById(intval($cookie->id_lang));
		$divLangName = 'title¤subheading¤cpara¤logo_subheading';

		/* xml loading */
		$xml = false;
		if (file_exists(dirname(__FILE__).'/cleancarroussel.xml'))
				if (!$xml = simplexml_load_file(dirname(__FILE__).'/cleancarroussel.xml'))
					$this->_html .= $this->displayError($this->l('Your editor file is empty.'));

		$carouselCurrentHook = Configuration::get('WHERE_TO_HOOK'); 		
		$options = array ('home', 'top');
		$fields = '<select name="where_hook">';
		
		foreach ($options as $opt)
		{
		 	$fields .= '<option value="'.$opt.'"';
			
			if ($carouselCurrentHook == $opt)
			{
				$fields .= ' selected="selected"';
			}

			$fields .= '>'.$opt.'</option>';
		}
		
		$fields .= '</select>';
		
		$this->_html .= '
		<form method="post" action="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data">
			<fieldset style="width: 900px;position:relative">
					<div style="position:absolute;top:30px;right:30px;width:300px;height:100px;color:red">*The default/max number of elements for this Carroussel is 5 but <strong>You can decrease the number of the elements by simply removing the image of the Item</strong>"</div> 
			
					<legend><img src="'.$this->_path.'logo.gif" alt="" title="" /> '.$this->displayName.'</legend>';
		$this->_html .= '
					<h3 style="margin-left:100px;">Module basic configuration</h3>
					<label style="width:130px;">'.$this->l('Append to ').'</label>
					<div class="margin-form" style="padding-left:100px;">'
						. $fields . 
					'
						</select>
						<p class="clear">'.$this->l('This defines where your carousel should appear').'</p>
					</div>

					<label style="width:130px;">'.$this->l('Width').'</label>
					<div class="margin-form" style="padding-left:100px;">
						<input type="text" name="carroussel_width" value="'.Configuration::get('CLEAN_CAR_WIDTH').'" />
					<p class="clear">'.$this->l('The Carroussel Images Width').'</p>
					</div>
					
					<label style="width:130px;">'.$this->l('Height').'</label>
					<div class="margin-form" style="padding-left:100px;">
						<input type="text" name="carroussel_height" value="'.Configuration::get('CLEAN_CAR_HEIGHT').'" />
					<p class="clear">'.$this->l('The Carroussel Images Height').'</p>
					</div>
					
					<label style="width:130px;">'.$this->l('Change Speed').'</label>
					<div class="margin-form" style="padding-left:100px;">
						<input type="text" name="carroussel_speed" value="'.Configuration::get('CLEAN_CHANGE_SPEED').'" />
					<p class="clear">'.$this->l('The timing before going to the next Carroussel Item (in miliseconds)').'</p>
					</div>
					
					<div class="margin-form clear" style="padding-left:100px;"><input type="submit" name="submitUpdate" value="'.$this->l('Save').'" class="button" /></div><br/><br/>';
					
					$totalDestaques = 5;
					
					for ($i=0; $i<$totalDestaques; $i++){
						$item = $i + 1;
						/* Primeiro Destaque */
						$this->_html .='
							<h3 class="clear" style="margin-left:100px;">Carroussel Item '.$item.'</h3>
							<label style="width:130px;">'.$this->l('Title').'</label>
							<div class="margin-form" style="padding-left:100px;">';
							
					$this->_html .= '
							<div id="title_'.$i.'">
								<input type="text" name="body_title_'.$i.'" id="body_title_'.$i.'" size="64" value="'.($xml ? stripslashes(htmlspecialchars($xml->body->{'title_'.$i})) : '').'" />
							</div>';
			
					$this->_html .= '
								<p class="clear">'.$this->l('Its the mouseover message that appears on the Image').'</p>
								<div class="clear"></div>
							</div>';
									
					$this->_html .= '
							<label style="width:130px;">'.$this->l('Link').'</label>
							<div class="margin-form" style="padding-left:100px;">
								<input type="text" name="body_home_logo_link_'.$i.'" size="64" value="'.($xml ? stripslashes(htmlspecialchars($xml->body->{'home_logo_link_'.$i})) : '').'" />
								<p style="clear: both">'.$this->l('Leave an "#" if you dont want this image to link to any page').'</p>
							</div>
							<div class="clear"></div>
							<label style="width:135px;">'.$this->l('Image').' </label>

							<div class="margin-form" style="padding-left:100px;">
									<img src="'.$this->_path.'carroussel_'.$i.'.jpg?t='.time().'" />
									<br/>					
									<input type="file" name="body_carroussel_'.$i.'" />
									<a href="'.$_SERVER['REQUEST_URI'].'&deleteImage'.$i.'" onClick="return confirm(\''.$this->l('Are you sure?', __CLASS__, true, false).'\');">
									<img src="../img/admin/delete.gif" alt="'.$this->l('Delete').'" /> '.$this->l('Delete').'</a>
									<p style="clear: both">'.$this->l('The Image Size should be equal to the Widht and Height defined. Deleting the above image will remove this Highlight from the Carousel.').'</p>
								<div class="clear"></div>
							</div>
							<div class="margin-form clear" style="padding-left:100px;"><input type="submit" name="submitUpdate" value="'.$this->l('Update').'" class="button" /></div>
							<br/><br/>';
					}

		/* Final dos destaques */
		$this->_html .= '
			<div class="clear pspace"></div>
			<div class="margin-form clear" style="padding-left:100px;"><input type="submit" name="submitUpdate" value="'.$this->l('Update All').'" class="button" /></div>
			</fieldset>
		</form>';
	}
	public function hookHeader($params)
	{
		Tools::addJS(($this->_path).'js/jquery.slider.min.js');
		Tools::addCSS(($this->_path).'cleancarroussel.css', 'all');
	}
	
	public function hookFooter($params)
	{
		global $smarty;
		$likeExists = Module::isInstalled('likealotmodule');
		
		if($likeExists && Configuration::get('LIKE_SHARE'))
			return;
		
		$thanks = '<span style="font-size:11px;font-color:#999999;font-style:italic;margin-top:11px;float:left"></a></span>';
        $smarty->assign(array('clear' => $thanks));
		return $this->display(__FILE__, 'protected.tpl');
	}
	
	public function hookHome($params)
	{
		$where = Configuration::get('WHERE_TO_HOOK');
		
		if($where == 'home')
			return $this->showCarousel($params);
		else
			return;
	}
	
	public function hookTop($params)
	{
		$where = Configuration::get('WHERE_TO_HOOK');
		
		if ($where == 'top')
			return $this->showCarousel($params);
		else
			return;
	}
	
	public function showCarousel($params)
	{
		if (file_exists('modules/cleancarroussel/cleancarroussel.xml'))
		{
			if ($xml = simplexml_load_file('modules/cleancarroussel/cleancarroussel.xml'))
			{
				$destaques = array(
					0 => array (
						'logo' => file_exists('modules/cleancarroussel/carroussel_0.jpg'),
						'logo_link' => $xml->body->home_logo_link_0,
						'logo_title' => $xml->body->title_1_0
						),
					1 => array (
						'logo' => file_exists('modules/cleancarroussel/carroussel_1.jpg'),
						'logo_link' => $xml->body->home_logo_link_1,
						'logo_title' => $xml->body->title_1_1
						),
					2 => array (
						'logo' => file_exists('modules/cleancarroussel/carroussel_2.jpg'),
						'logo_link' => $xml->body->home_logo_link_2,
						'logo_title' => $xml->body->title_1_2
						),
					3 => array (
						'logo' => file_exists('modules/cleancarroussel/carroussel_3.jpg'),
						'logo_link' => $xml->body->home_logo_link_3,
						'logo_title' => $xml->body->title_1_3
						),
					4 => array (
						'logo' => file_exists('modules/cleancarroussel/carroussel_4.jpg'),
						'logo_link' => $xml->body->home_logo_link_4,
						'logo_title' => $xml->body->title_1_4
						),
				);
				
				global $cookie, $smarty;
				$smarty->assign(array(
					'width' => Configuration::get('CLEAN_CAR_WIDTH'),
					'height' => Configuration::get('CLEAN_CAR_HEIGHT'),
					'xml' => $xml,
					'destaques' => $destaques,
					'changeSpeed' => Configuration::get('CLEAN_CHANGE_SPEED'),
					'this_path' => $this->_path
				));
				return $this->display(__FILE__, 'cleancarroussel.tpl');
			}
		}
		return false;
	}
}