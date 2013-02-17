<?php

/**
  *
  * @author     Ireneusz Kierkowski <ircykk@gmail.pl>
  * @copyright  2012 Ireneusz Kierkowski
  * @link       http://ircykk.pl, http://addonspresta.com
  * @package    ShipToPay PrestaShop Module   
  * @version    1.10
  * @date       29-03-2012
  * 
  */
  
class Shiptopay extends Module{
    
	function __construct(){
		$this->name = 'shiptopay';
		$this->tab = 'administration';
		$this->version = '1.10';

		parent::__construct();

		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Ship to Pay');
		$this->description = $this->l('Module that matches shippment to payment (for PrestaShop 1.4x).');
	}

	function install(){
		if (!parent::install()){
			return false;
		}
        
        file_put_contents('../override/classes/Module.php', file_get_contents('../modules/shiptopay/Module.php'));
        
        Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('CREATE TABLE `'._DB_PREFIX_.'shiptopay` (
            `id_carrier` INT( 11 ) NOT NULL,
            `id_payment` INT( 11 ) NOT NULL
            ) ENGINE = MYISAM');
        
		return true;
	}
	public function uninstall(){
		
		if (!parent::uninstall()){
			return false;
			}
            
        Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('DROP TABLE '._DB_PREFIX_.'shiptopay');
		Configuration::deleteByName('SHIPTOPAY_ACTIVE');
        
		return true;	
			
	}
    
	function getContent(){
		
		$output = '<h2>'.$this->displayName.' (v'.$this->version.')</h2>
        <p>'.$this->description.'</p>
        <p>'.$this->l('For more PrestaShop modules visit ').'<b><a href="http://addonspresta.com?from='.$this->name.'" TARGET="_blank" >'.$this->l('AddonsPresta.com').'</a></b></p><br />';
		
        if(!file_exists('../override/classes/Module.php')){
            $output .= '<h2 style="color:red;">'.$this->l('The module is not properly installed!').'</h2>';
            $output .= '<h3 style="color:red;">'.$this->l('Move file').' <b style="color: black;">'.__PS_BASE_URI__.'modules/shiptopay/Module.php</b> '.$this->l('to').' <b style="color: black;">'.__PS_BASE_URI__.'override/classes/</b></h2>';
        }
        
		if(Tools::isSubmit('saveShipToPay')){
		  
            $data = $_POST['data'];
            
            Configuration::updateValue('SHIPTOPAY_ACTIVE', $_POST['SHIPTOPAY_ACTIVE']);
            
            if(count($data)){
                
                Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('TRUNCATE TABLE '._DB_PREFIX_.'shiptopay');
                
                foreach($data as $carrier => $payments){
                    if(count($payments)){
                        foreach($payments as $payment => $val){
                            if($val == 1 && $carrier && $payment){
                                Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('INSERT INTO '._DB_PREFIX_.'shiptopay VALUES ('.intval($carrier).', '.intval($payment).')');
                            }
                        }
                    }
                }
            }
		}
        
		$output .= $this->infoForm();
		return $output;
	}

	/**
	* Returns module content
	*
	* @param array $params Parameters
	* @return string Content
	*/
	function infoForm(){
	   
		global $cookie;
        
    	$carr = $this->getCarriers($cookie->id_lang,1, false, false);
    	$pay = $this->getPayment();
    	
    	$output= '<script type="text/javascript" src="'.__PS_BASE_URI__.'modules/shiptopay/shiptopay.js"></script>';
    	$output.= '<link type="text/css" rel="stylesheet" href="'.__PS_BASE_URI__.'modules/shiptopay/shiptopay.css" />';
    	
    	$output .= '<fieldset><legend>'.$this->l('Settings').'</legend><ul class="listashiptopay">
        <form method="post" action="'.Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']).'"" >';         

		$output .= '<label>'.$this->l('Active module').'</label>
        <div class="margin-form">
			<input type="radio" value="1" id="SHIPTOPAY_ACTIVE_on" name="SHIPTOPAY_ACTIVE" '.(Configuration::get('SHIPTOPAY_ACTIVE')==1 ? 'checked="checked"' : '').'>
			<label for="SHIPTOPAY_ACTIVE_on" class="t"> <img title="'.$this->l('Yes').'" alt="'.$this->l('Yes').'" src="../img/admin/enabled.gif"></label>
			<input type="radio" value="0" id="SHIPTOPAY_ACTIVE_off" name="SHIPTOPAY_ACTIVE" '.(Configuration::get('SHIPTOPAY_ACTIVE')!=1 ? 'checked="checked"' : '').'>
			<label for="SHIPTOPAY_ACTIVE_off" class="t"> <img title="'.$this->l('No').'" alt="'.$this->l('No').'" src="../img/admin/disabled.gif"></label>
            <p>'.$this->l('If you disable the module, all payments will be made available regardless of the setting.').'</p>
        </div>
        
        <hr />';

        $output .= '<table cellspacing="0" cellpadding="0" class="table">';
        
        $output .= '<tr>';
        $output .= '<th>'.$this->l('Carriers').'</th>';
    	foreach($pay as $pk => $payment){
    	   $output .= '<th><img src="'.__PS_BASE_URI__.'modules/'.$payment['pay_name'].'/logo.gif" title="'.$payment['name'].'" alt="'.$payment['pay_name'].'" /></th>';
    	}
        $output .= '</tr>';
        
    	foreach($carr as $ck => $carrier){
    	   	$output .= '<tr '.($ck%2 == 1 ? 'class="alt_row"' : '').'>';
            $output .= '<td>'.$carrier['name'].'</td>';
            
        	foreach($pay as $pk => $payment){
        	   $checked = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'shiptopay` WHERE id_carrier = '.intval($carrier['id_carrier']).' AND id_payment = '.intval($payment['id_module'])) ? true : false;
        	   $output .= '<td style="text-align: center;"><input type="checkbox" name="data['.$carrier['id_carrier'].']['.$payment['id_module'].']" value="1" '.($checked ? 'checked="checked"' : '').' /></td>';
        	}
            
            $output .= '</tr>';
    	}
        
        $output .= '</table><br />';        
    	$output.= '<input type="submit" class="button pointer" name="saveShipToPay" value="'.$this->l('Save combinations').'" />
        </form></fieldset>';
        
    	return $output;
	
	}
    
	public static function getCarriers($id_lang, $active = false, $delete = false, $id_zone = false){
	   
	 	if (!Validate::isBool($active))
	 		die(Tools::displayError());
		$sql = '
			SELECT c.*, cl.delay
			FROM `'._DB_PREFIX_.'carrier` c
			LEFT JOIN `'._DB_PREFIX_.'carrier_lang` cl ON (c.`id_carrier` = cl.`id_carrier` AND cl.`id_lang` = '.$id_lang.')
			LEFT JOIN `'._DB_PREFIX_.'carrier_zone` cz  ON (cz.`id_carrier` = c.`id_carrier`)'.
			($id_zone ? 'LEFT JOIN `'._DB_PREFIX_.'zone` z  ON (z.`id_zone` = '.$id_zone.')' : '').'
			WHERE c.`deleted` '.($delete ? '= 1' : ' = 0').
			($active ? ' AND c.`active` = 1' : '').
			($id_zone ? ' AND cz.`id_zone` = '.$id_zone.'
			AND z.`active` = 1' : '').'
			GROUP BY c.`id_carrier`';
		$carriers = Db::getInstance()->ExecuteS($sql);
		foreach ($carriers as $key => $carrier)
			if ($carrier['name'] == '0')
				$carriers[$key]['name'] = Configuration::get('PS_SHOP_NAME');
		return $carriers;
	}
    
	public static function getPayment(){
	 	$payments = Db::getInstance()->ExecuteS('
    		SELECT *, m.name as pay_name
    		FROM `'._DB_PREFIX_.'module` m
    		LEFT JOIN `'._DB_PREFIX_.'hook_module` hm ON m.`id_module` = hm.`id_module`
    		LEFT JOIN `'._DB_PREFIX_.'hook` k ON hm.`id_hook` = k.`id_hook`
    		WHERE k.`position` = 1 AND k.id_hook = 1');
		
		foreach ($payments as $k => $paymod){
			require_once _PS_MODULE_DIR_.'/'.$paymod['pay_name'].'/'.$paymod['pay_name'].'.php';
            $module = get_object_vars(Module::getInstanceByName($paymod['pay_name']));
			$payments[$k]['name'] = $module['displayName'];
		}
        
		return $payments;
	}
}
