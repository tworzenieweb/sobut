<?php
/*
* 2007-2010 PrestaShop 
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author Prestashop SA <contact@prestashop.com>
*  @copyright  2007-2010 Prestashop SA
*  @version  Release: $Revision: 1.4 $
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_CAN_LOAD_FILES_'))
	exit;

class HomeNewProducts extends Module
{
	private $_html = '';
	private $_postErrors = array();

	function __construct()
	{
		$this->name = 'homenewproducts';
		$this->tab = 'front_office_features';
		$this->version = '1.0';

		parent::__construct();
		
		$this->displayName = $this->l('New Products on the homepage');
		$this->description = $this->l('Displays New Products in the middle of your homepage');
	}

	function install()
	{
        if (parent::install() == false OR $this->registerHook('home') == false OR Configuration::updateValue('NEW_PRODUCTS_NBR', 4) == false)
		return false;
	return true;
	}

	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submitHomeNewProducts'))
		{
			if (!$productNbr = Tools::getValue('productNbr') OR empty($productNbr))
				$output .= '<div class="alert error">'.$this->l('You should fill the "products displayed" field').'</div>';
			elseif (intval($productNbr) == 0)
				$output .= '<div class="alert error">'.$this->l('Invalid number.').'</div>';
			else
			{
				Configuration::updateValue('NEW_PRODUCTS_NBR', intval($productNbr));
				$output .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('Settings updated').'</div>';
			}           
		}
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		$output = '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset><legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>
				<label>'.$this->l('Products displayed').'</label>
				<div class="margin-form">
					<input type="text" name="productNbr" value="'.intval(Configuration::get('NEW_PRODUCTS_NBR')).'" />
					<p class="clear">'.$this->l('Set the number of products to be displayed in this block').'</p>
				</div>
				<center><input type="submit" name="submitHomeNewProducts" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>
		</form>';
		return $output;
	}

	function hookHome($params)
	{
		global $smarty;

		$newProducts = Product::getNewProducts(intval($params['cookie']->id_lang), 0, intval(Configuration::get('NEW_PRODUCTS_NBR')));
                
                
		$smarty->assign(array('new_products' => $newProducts, 'homeSize' => Image::getSize('home')));

		return $this->display(__FILE__, 'homenewproducts.tpl');
	}
}
