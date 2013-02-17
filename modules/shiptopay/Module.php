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

abstract class Module extends ModuleCore {
    public static function hookExecPayment(){
        
		global $cart, $cookie;

		$hookArgs = array('cookie' => $cookie, 'cart' => $cart);
		$output = '';

        if(Configuration::get('SHIPTOPAY_ACTIVE')){
            
    		$id_customer = (int)($cookie->id_customer);
    		$billing = new Address((int)($cart->id_address_invoice));
            
    		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
    		SELECT DISTINCT(stp.id_carrier), h.`id_hook`, m.`name`, hm.`position`
    		FROM `'._DB_PREFIX_.'module_country` mc
    		LEFT JOIN `'._DB_PREFIX_.'module` m ON m.`id_module` = mc.`id_module`
    		INNER JOIN `'._DB_PREFIX_.'module_group` mg ON (m.`id_module` = mg.`id_module`)
    		INNER JOIN `'._DB_PREFIX_.'customer_group` cg on (cg.`id_group` = mg.`id_group` AND cg.`id_customer` = '.(int)($id_customer).')
    		LEFT JOIN `'._DB_PREFIX_.'hook_module` hm ON hm.`id_module` = m.`id_module`
    		LEFT JOIN `'._DB_PREFIX_.'hook` h ON hm.`id_hook` = h.`id_hook` 
            LEFT JOIN `'._DB_PREFIX_.'shiptopay` stp ON hm.`id_module` = stp.`id_payment` 
    		WHERE h.`name` = \'payment\'
    		AND mc.id_country = '.(int)($billing->id_country).'
    		AND m.`active` = 1 
            AND stp.id_carrier='.intval($cart->id_carrier).' 
    		ORDER BY hm.`position`, m.`name` DESC');
           
        }else{    
            $result = parent::getPaymentModules();
        }
        
		if ($result)
			foreach ($result AS $k => $module)
				if (($moduleInstance = Module::getInstanceByName($module['name'])) AND is_callable(array($moduleInstance, 'hookpayment')))
					if (!$moduleInstance->currencies OR ($moduleInstance->currencies AND sizeof(Currency::checkPaymentCurrencies($moduleInstance->id))))
						$output .= call_user_func(array($moduleInstance, 'hookpayment'), $hookArgs);
                        
		return $output;
	}
}
