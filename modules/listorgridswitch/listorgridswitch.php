<?php

/**
  * listorgrid class, listorgrid.php
  * listorgrid
  *
  * @author Jolvil, (d)oekia
  * @copyright Jolvil, (d)oekia, 2011
  * @version 1.9
  * 
  * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
  * credits:
  *    - Soh Tanaka - http://designm.ag/tutorials/jquery-display-switch/
  * 
  * Change logs: (last first)
  *   jv: 02-03-2012: 1.9  Bug fix with Switch View text translation fixed
  *   jv: 25-11-2012: 1.8  Display issue on manufacturer list fixed
  *   dk: 08-12-2011: 1.7  Fixes issues on non DOCUMENT_ROOT based shop
  *   dk: 07-12-2011: 1.6  Changes installation and template processing for hosters compatibility
  *   dk: 06-12-2011: 1.5  Fixes retro-compatibilty for smarty v2
  *   dk: 02-12-2011: 1.4  Add cookie value to preserve setting accross navigation
  *   dk: 02-12-2011: 1.3  Enhanced installation
  *   jv: 25-11-2011: 1.2  Various bug fixes
  *   jv: 25-11-2011: 1.1  Initial
  *
  * declares:
  *   class listorgridswitch     - the Prestashop module
  */

if (false)
{
  error_reporting(E_ALL | E_STRICT );
  set_error_handler(create_function('','{ error_reporting(0); restore_error_handler(); while (ob_get_level()) ob_end_flush(); echo \'<pre>\'.print_r(debug_backtrace(),true).\'</pre>\'; exit(0); }'));
}

if (!class_exists('Module',false))
{
	// This inelegant inclusion method allow to service the file even in development
	// environment when the module is in fact a symbolic link inside the hosting DOCUMENT_ROOT
	require_once(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/config/config.inc.php');
	require_once(_PS_ROOT_DIR_.'/init.php');
}

class listorgridswitch extends Module
{
	const HOOKS = 'header';
	public static $_templates = array(
	   'product-sort' =>  '{cycle name="clorg" values="prolog,epilog" assign="clorg"}{include file="$lorg_tpldir./modules/listorgridswitch/listorgridswitch.tpl" cycle=$clorg}',
	   'pagination'   =>  '{cycle name="clorg" values="prolog,epilog" assign="clorg"}{include file="$lorg_tpldir./modules/listorgridswitch/listorgridswitch.tpl" cycle=$clorg}',
	);
	
	public function __construct()
	{
		$this->name = 'listorgridswitch';
		$this->author = 'Jolvil, (d)oekia';
		if(version_compare(_PS_VERSION_, '1.4') >= 0)
			$this->tab = 'front_office_features';
		else
			$this->tab = 'Blocks';
		$this->version = '1.9';

		parent::__construct();

		$this->displayName = $this->l('List or Grid Switch');
		$this->description = $this->l('Displays product listing as a list or a grid using jQuery');
	}
	
	public function install()
	{
		$errors = array();
		for(;;)
		{
			if (!parent::install())
			$errors[] = $this->l('Prestashop rejected module installation');
		
			foreach(explode(',',self::HOOKS) as $hook)
			if (!$this->registerHook($hook))
			$errors[] = $this->l('failed installing hook').' ('.$hook.')';
		
			if (sizeof($errors)) break;
		
			self::tuneTemplate();
		
			return true;
		}
		$errors[] = $this->l(' --> Rollback install');
		
		echo '<div class="alert"><pre>'.
		get_class($this).': '.
		implode("\n".get_class($this).': ',
		array_map('htmlentities',$errors)).
		         '</pre></div>'."\n";
		
		$this->uninstall();
		return false;
	}
	
  private function tuneTemplate()
  {
  	global $smarty;
  	foreach(self::$_templates as $tname => $taddon)
  	{
  	   $template = file_get_contents(_PS_THEME_DIR_.$tname.'.tpl');
  	   if (strpos($template,$taddon) === false)
  	     file_put_contents(_PS_THEME_DIR_.$tname.'.tpl',$taddon."\n".$template);
  	   
  	   if (is_callable(array($smarty,'clear_cache')))
  	     $smarty->clear_cache(_PS_THEME_DIR_.$tname.'.tpl');
  	   if (is_callable(array($smarty,'clearCache')))
  	     $smarty->clearCache(_PS_THEME_DIR_.$tname.'.tpl');
  	}
  }
  
  private function untuneTemplate()
  {
    global $smarty;
  	foreach(self::$_templates as $tname => $taddon)
  	{
  	   $template = file_get_contents(_PS_THEME_DIR_.$tname.'.tpl');
  	   $template = str_replace($taddon."\n",'',$template);
 	     file_put_contents(_PS_THEME_DIR_.$tname.'.tpl',$template);

  	   if (is_callable(array($smarty,'clear_cache')))
  	     $smarty->clear_cache(_PS_THEME_DIR_.$tname.'.tpl');
  	   if (is_callable(array($smarty,'clearCache')))
  	     $smarty->clearCache(_PS_THEME_DIR_.$tname.'.tpl');
 	  }
  }
  
	public function uninstall()
  {
    $ret = true;
    $ret &= parent::uninstall(); // Automatically take care of the hooks
    
    self::untuneTemplate();
    return $ret;
  }
	
	static function moduleFilepath()
	{
		return dirname(__FILE__).'/'.basename(dirname(__FILE__)).'.php';
	}
	
	static function moduleBaseURI()
	{
		if (file_exists(_PS_THEME_DIR_.'modules/'.basename(dirname(__FILE__))))
		return  _THEMES_DIR_._THEME_NAME_.'/modules/'.basename(dirname(__FILE__)).'/';
		else
		return _MODULE_DIR_.basename(dirname(__FILE__)).'/';
	}
	
	public function hookHeader()
	{
		global $smarty, $cookie;
		
		$base_from_root = self::moduleBaseURI();
		if ( strpos($base_from_root, __PS_BASE_URI__) === 0)
		  $base_from_root = substr($base_from_root,strlen(__PS_BASE_URI__));
		
		if (dirname(_PS_ROOT_DIR_.'/'.$base_from_root.'/tpl') == dirname(_PS_ROOT_DIR_.'/modules/'.$this->name.'/tpl'))
		  $tpldir = _PS_ROOT_DIR_.'/';
		else
		  $tpldir = _PS_ROOT_DIR_._THEME_DIR_;
		
		$smarty->assign(array(
		  'listorgridmode' => (int)($cookie->listorgridmode),
		  'lorg_tpldir'    => $tpldir,
		));
		
	  if (version_compare(_PS_VERSION_,'1.4','<'))
    {
    	$css_files = $js_files = array();
    	
      $css_files[(file_exists(_PS_THEME_DIR_._MODULE_DIR_.$this->name.'/'.$this->name.'.css')
                   ?_THEME_DIR_._MODULE_DIR_.$this->name.'/'.$this->name.'.css'
                   :_MODULE_DIR_.$this->name.'/'.$this->name.'.css')] = 'all';

      $js_files[] = ($this->_path).($this->name).'.js';
      // Some collision occurs when the page is order.php and a template uses the name 'header.tpl'
      // This whipe-out the $css_files, $js_files global ?!?
      // Implement a workarround
      $html = '';
      foreach($css_files as $uri => $media)
      {
        $html .= "<link href='{$uri}' rel='stylesheet' type='text/css' media='{$media}' />\n";
      }
      foreach($js_files as $uri)
      {
        $html .= "<script type='text/javascript' src='{$uri}'></script>\n";
      }
      return $html;
    }
    else
    {
      Tools::addCSS(($this->_path).($this->name).'.css', 'all');
      Tools::addJS(($this->_path).($this->name).'.js');
    }
	}
	public static function processRequestStatic() {
		$mod = new self(); $mod->processRequest();
	}
	public function processRequest()
	{
		global $cookie;
		 
		$errors = array();
		if ( isset($_REQUEST['listorgridajax']))
		{
			$mode = Tools::getValue('listorgridmode',0);
  		$cookie->listorgridmode = (int)($mode);
			die('{ "listorgridmode": '.(int)$mode.' }');
		}
	}
}
	
if (isset($_REQUEST['listorgridajax']))
{
	listorgridswitch::processRequestStatic();
}
