<?php
/*
* 2007-2012 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2012 PrestaShop SA
*  @version  Release: $Revision: 14011 $
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

include_once(_PS_MODULE_DIR_.'paypal/paypal.php');
include_once(_PS_MODULE_DIR_.'paypal/api/paypal_lib.php');

class PaypalExpressCheckout extends Paypal
{
	public $logs = array();

	public $method_version = '84';

	public $method;

	/** @var currency Currency used for the payment process **/
	public $currency;

	/** @var decimals Used to set prices precision **/
	public $decimals;

	/** @var result Contains the last request result **/
	public $result;

	/** @var token Contains the last token **/
	public $token;

	// Depending of the type set, id_cart or id_product will be set
	public $id_cart;

	// Depending of the type set, id_cart or id_product will be set
	public $id_product;

	public $id_p_attr;

	public $quantity;

	public $payer_id;

	public $available_type = array('cart', 'product', 'payment_cart');

	public $total_different_product;

	public $product_list = array();

	// Used to know if user can validated his payment after shipping / address selection
	public $ready = false;

	// Take for now cart or product value
	public $type = false;

	static public $COOKIE_NAME = 'express_checkout';

	public $cookie_key = array(
		'token', 'id_product', 'id_p_attr',
		'quantity', 'type', 'total_different_product',
		'secure_key', 'ready', 'payer_id'
	);

	public function __construct($type = false)
	{
		parent::__construct();

		// If type is sent, the cookie has to be delete
		if ($type)
		{
			unset($this->context->cookie->{PaypalExpressCheckout::$COOKIE_NAME});
			$this->setExpressCheckoutType($type);
		}

		// Store back the PayPal data if present under the cookie
		if (isset($this->context->cookie->{PaypalExpressCheckout::$COOKIE_NAME}))
		{
			$paypal = unserialize($this->context->cookie->{PaypalExpressCheckout::$COOKIE_NAME});

			foreach ($this->cookie_key as $key)
			{
				$this->{$key} = $paypal[$key];
			}
		}

		$this->currency = new Currency((int)$this->context->cart->id_currency);

		if (!Validate::isLoadedObject($this->currency))
		{
			$this->_errors[] = $this->l('Not a valid currency');
		}

		if (sizeof($this->_errors))
		{
			return false;
		}

		$currency_decimals	= is_array($this->currency) ? (int)$this->currency['decimals'] : (int)$this->currency->decimals;
		$this->decimals		= $currency_decimals * _PS_PRICE_DISPLAY_PRECISION_;
	}

	// Will build the product_list depending of the type
	private function initParameters($need_init = false)
	{
		switch($this->type)
		{
			case 'product':

				if ($need_init)
				{
					$this->id_product = (int)Tools::getValue('id_product');
					$this->quantity = (int)Tools::getValue('quantity');
					$this->id_p_attr = (int)(Tools::getValue('id_p_attr') ? Tools::getValue('id_p_attr') : $this->id_p_attr);
				}

				$product = new Product((int)$this->id_product);
				if (!$product || !$this->quantity)
				{
					return false;
				}

				// Build a product array with needed values
				$this->product_list[] = array(
					'id_product'			=> $product->id,
					'id_product_attribute'	=> $this->id_p_attr,
					'quantity'				=> $this->quantity,
					'name'					=> $product->name[$this->context->language->id],
					'description_short'		=> $product->description[$this->context->language->id],
					'price'					=> $product->getPrice(false, $this->id_p_attr, 2),
					'price_wt'				=> $product->getPrice(true, $this->id_p_attr, 2),
				);

				$this->product_list[0]['total']		= Tools::ps_round($this->product_list[0]['price'] * (int)$this->quantity, 2);
				$this->product_list[0]['total_wt']	= $this->product_list[0]['price_wt'] * (int)($this->quantity);

				break;

			case ('cart' || 'payment_cart') :

				if (!$this->context->cart || !$this->context->cart->id)
				{
					return false;
				}

				$this->product_list = $this->context->cart->getProducts();

				break;
		}

		if (!count($this->product_list))
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	public function setExpressCheckout()
	{
		$this->method			= 'SetExpressCheckout';
		$fields['CANCELURL']	= Tools::getValue('current_shop_url');

		// Only this call need to get the value from the $_GET / $_POST array
		if (!$this->initParameters(true) || !$fields['CANCELURL'])
		{
			return false;
		}

		// Set payment detail (reference)
		$this->_setPaymentDetails($fields);
		$fields['SOLUTIONTYPE']	= 'Sole';
		$fields['LANDINGPAGE']	= 'Login';

		// Seller informations
		$fields['USER']			= Configuration::get('PAYPAL_API_USER');
		$fields['PWD']			= Configuration::get('PAYPAL_API_PASSWORD');
		$fields['SIGNATURE']	= Configuration::get('PAYPAL_API_SIGNATURE');

		$this->callAPI($fields);
		$this->_storeToken();
	}

	public function getExpressCheckout()
	{
		$this->method		= 'GetExpressCheckoutDetails';
		$fields['TOKEN']	= $this->token;

		$this->initParameters();
		$this->callAPI($fields);

		// The same token of SetExpressCheckout
		$this->_storeToken();
	}

	public function doExpressCheckout()
	{
		$this->method = 'DoExpressCheckoutPayment';

		$fields['TOKEN']	= $this->token;
		$fields['PAYERID']	= $this->payer_id;

		if (count($this->product_list) <= 0)
		{
			$this->initParameters();
		}

		// Set payment detail (reference)
		$this->_setPaymentDetails($fields);
		$this->callAPI($fields);

		$this->result += $fields;
	}

	private function callAPI($fields)
	{
		$this->logs		= array();
		$paypal_lib		= new PaypalLib();

		$this->result	= $paypal_lib->makeCall($this->getAPIURL(), $this->getAPIScript(), $this->method, $fields, $this->method_version);
		$this->logs		= array_merge($this->logs, $paypal_lib->getLogs());

		$this->_storeToken();
	}

	private function _setPaymentDetails(&$fields)
	{
		// Setting Express Checkout return url
		$shop_url = PayPal::getShopDomainSsl(true, true);

		// Required field
		$fields['RETURNURL']			= $shop_url . _MODULE_DIR_ . $this->name . '/express_checkout/submit.php';
		$fields['REQCONFIRMSHIPPING']	= '0';
		$fields['NOSHIPPING']			= '1';
		$fields['BUTTONSOURCE']			= TRACKING_CODE;

		// Products
		$taxes	= 0;
		$total	= 0;
		$index	= -1;

		$id_address = (int)$this->context->cart->id_address_invoice;
		$this->setShippingAddress($fields, $id_address);

		// Set cart products list
		$this->setProductsList($fields, $index, $total, $taxes);
		$this->setDiscountsList($fields, $index, $total, $taxes);
		$this->setGiftWrapping($fields, $index, $total);

		// Payment values
		$this->setPaymentValues($fields, $total, $taxes);

		foreach ($fields as &$field)
		{
			if (is_numeric($field))
			{
				$field = str_replace(',', '.', $field);
			}
		}
	}

	private function setShippingAddress(&$fields, $id_address)
	{
		if (! $id_address)
			return;

		$address	= new Address($id_address);

		$fields['ADDROVERRIDE']							= '1';
		$fields['PAYMENTREQUEST_0_SHIPTOSTREET']		= $address->address1;
		$fields['PAYMENTREQUEST_0_SHIPTOSTREET2']		= $address->address2;
		$fields['PAYMENTREQUEST_0_SHIPTOCITY'] 			= $address->city;

		if ($address->id_state)
		{
			$state	= new State((int)$address->id_state);
			$fields['PAYMENTREQUEST_0_SHIPTOSTATE'] 	= $state->name;
		}

		$country	= new Country((int)$address->id_country);
		$fields['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE']	= $country->iso_code;
		$fields['PAYMENTREQUEST_0_SHIPTOZIP'] 			= $address->postcode;
	}

	private function setProductsList(&$fields, &$index, &$total, &$taxes)
	{
		foreach ($this->product_list as $product)
		{
			$fields['L_PAYMENTREQUEST_0_NUMBER'.++$index]	= $product['id_product'];

			$fields['L_PAYMENTREQUEST_0_NAME'.$index]		= $product['name'];
			$fields['L_PAYMENTREQUEST_0_DESC'.$index]		= substr(strip_tags($product['description_short']), 0, 120).'...';

			$fields['L_PAYMENTREQUEST_0_AMT'.$index]		= Tools::ps_round($product['price'], $this->decimals);
			$fields['L_PAYMENTREQUEST_0_TAXAMT'.$index]		= Tools::ps_round($product['price_wt'] - $product['price'], $this->decimals);
			$fields['L_PAYMENTREQUEST_0_QTY'.$index]		= Tools::ps_round($product['quantity'], $this->decimals);

			$taxes	= Tools::ps_round($taxes + ($fields['L_PAYMENTREQUEST_0_TAXAMT'.$index] * $fields['L_PAYMENTREQUEST_0_QTY'.$index]), $this->decimals);
			$total	= Tools::ps_round($total + ($fields['L_PAYMENTREQUEST_0_AMT'.$index] * $fields['L_PAYMENTREQUEST_0_QTY'.$index]), $this->decimals);
		}
	}

	private function setDiscountsList(&$fields, &$index, &$total, &$taxes)
	{
		$discounts = $this->context->cart->getDiscounts();

		if (sizeof($discounts) > 0)
		{
			foreach ($discounts as $discount)
			{
				$fields['L_PAYMENTREQUEST_0_NUMBER'.++$index]	= $discount['id_discount'];

				$fields['L_PAYMENTREQUEST_0_NAME'.$index]		= $discount['name'];
				$fields['L_PAYMENTREQUEST_0_DESC'.$index]		= substr(strip_tags($discount['description']), 0, 120).'...';

				/* It is a discount so we store a negative value */
				$fields['L_PAYMENTREQUEST_0_AMT'.$index]		= -1 * Tools::ps_round($discount['value_real'], $this->decimals);
				$fields['L_PAYMENTREQUEST_0_TAXAMT'.$index]		= -1 * (Tools::ps_round($discount['value_real'] - $discount['value_tax_exc'], $this->decimals));
				$fields['L_PAYMENTREQUEST_0_QTY'.$index]		= 1;

				$taxes	= Tools::ps_round($taxes + $fields['L_PAYMENTREQUEST_0_TAXAMT'.$index], $this->decimals);
				$total	= Tools::ps_round($total + $fields['L_PAYMENTREQUEST_0_AMT'.$index], $this->decimals);
			}
		}
	}

	private function setGiftWrapping(&$fields, &$index, &$total)
	{
		if ($this->context->cart->gift == 1)
		{
			$gift_wrapping_price = (float)Configuration::get('PS_GIFT_WRAPPING_PRICE');

			$fields['L_PAYMENTREQUEST_0_NAME'.++$index]	= $this->l('Gift wrapping');

			$fields['L_PAYMENTREQUEST_0_AMT'.$index]		= Tools::ps_round($gift_wrapping_price, $this->decimals);
			$fields['L_PAYMENTREQUEST_0_QTY'.$index]		= 1;

			$total = Tools::ps_round($total + $gift_wrapping_price, $this->decimals);
		}
	}

	private function setPaymentValues(&$fields, &$total, &$taxes)
	{
		if (_PS_VERSION_ < '1.5')
		{
			$shipping_cost		= $this->context->cart->getOrderShippingCost(null, false);
			$shipping_cost_wt	= $this->context->cart->getOrderShippingCost();
		}
		else
		{
			$shipping_cost	= $this->context->cart->getTotalShippingCost(null, true);
			$shipping_cost_wt	= $this->context->cart->getTotalShippingCost();
		}

		$fields['PAYMENTREQUEST_0_PAYMENTACTION']	= 'Sale';
		$fields['PAYMENTREQUEST_0_CURRENCYCODE']	= $this->currency->iso_code;

		$fields['PAYMENTREQUEST_0_SHIPPINGAMT']		= Tools::ps_round($shipping_cost_wt, $this->decimals);
		$shipping_tax 								= $shipping_cost_wt - $shipping_cost;

		$fields['PAYMENTREQUEST_0_ITEMAMT']			= Tools::ps_round($total, $this->decimals);
		$fields['PAYMENTREQUEST_0_TAXAMT']			= Tools::ps_round($taxes, $this->decimals);

		$total_order_amt							= $fields['PAYMENTREQUEST_0_ITEMAMT'] + $fields['PAYMENTREQUEST_0_TAXAMT'] + $fields['PAYMENTREQUEST_0_SHIPPINGAMT'];
		$fields['PAYMENTREQUEST_0_AMT']				= Tools::ps_round($total_order_amt, $this->decimals);
	}

	public function rightPaymentProcess()
	{
		$total = $this->getTotalPaid();

		// float problem with php, have to use the string cast.
		if ((isset($this->result['AMT']) && ((string)$this->result['AMT'] != (string)$total)) ||
			(isset($this->result['PAYMENTINFO_0_AMT']) && ((string)$this->result['PAYMENTINFO_0_AMT'] != (string)$total)))
		{
			return false;	
		}
		else
		{
			return true;
		}
	}

	/**
	 * @return mixed
	 */
	public function getTotalPaid()
	{
		$total = 0.00;

		foreach ($this->product_list as $product)
		{
			$price		= Tools::ps_round($product['price_wt'], $this->decimals);
			$quantity	= Tools::ps_round($product['quantity'], $this->decimals);
			$total		= Tools::ps_round($total + ($price * $quantity), $this->decimals);
		}
		
		$discounts = $this->context->cart->getDiscounts();
		if (sizeof($discounts) > 0)
		{
			foreach ($discounts as $product)
			{
				$price	= -1 * Tools::ps_round($product['value_real'], $this->decimals);
				$total	= Tools::ps_round($total + $price, $this->decimals);
			}
		}

		if ($this->context->cart->gift == 1)
		{
			$total += Configuration::get('PS_GIFT_WRAPPING_PRICE');
		}

		if (_PS_VERSION_ < '1.5')
		{
			return Tools::ps_round($this->context->cart->getOrderShippingCost(), $this->decimals) + $total;
		}
		else
		{
			return Tools::ps_round($this->context->cart->getTotalShippingCost(), $this->decimals) + $total;
		}
	}

	private function _storeToken()
	{
		if (is_array($this->result) && isset($this->result['TOKEN']))
		{
			$this->token = strval($this->result['TOKEN']);
		}
	}

	// Store data for the next reloading page
	private function _storeCookieInfo()
	{
		$tab = array();

		foreach ($this->cookie_key as $key)
		{
			$tab[$key] = $this->{$key};
		}

		$this->context->cookie->{PaypalExpressCheckout::$COOKIE_NAME} = serialize($tab);
	}

	public function hasSucceedRequest()
	{
		$ack_list = array('ACK', 'PAYMENTINFO_0_ACK');

		if (is_array($this->result))
		{
			foreach($ack_list as $key)
			{
				if (isset($this->result[$key]) && strtoupper($this->result[$key]) == 'SUCCESS')
				{
					return true;
				}
			}
		}

		return false;
	}

	private function getSecureKey()
	{
		if (!count($this->product_list))
		{
			$this->initParameters();
		}

		$key = array();

		foreach($this->product_list as $product)
		{
			$id_product				= $product['id_product'];
			$id_product_attribute	= $product['id_product_attribute'];
			$quantity				= $product['quantity'];

			$key[] = $id_product.$id_product_attribute.$quantity._COOKIE_KEY_;
		}

		return MD5(serialize($key));
	}

	public function isProductsListStillRight()
	{
		$key = $this->getSecureKey();

		if ($this->secure_key != $key)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	public function setExpressCheckoutType($type)
	{
		if (in_array($type, $this->available_type))
		{
			$this->type = $type;
			return true;
		}
		else
		{
			return false;
		}
	}

	public function redirectToAPI()
	{
		$this->secure_key = $this->getSecureKey();
		$this->_storeCookieInfo();

		header('Location: https://'.$this->getPayPalURL().'/websc&cmd=_express-checkout&token='.urldecode($this->token));

		exit(0);
	}

	public function redirectToCheckout($customer, $redirect = false)
	{
		$this->ready = true;
		$this->_storeCookieInfo();

		$this->context->cookie->id_customer			= (int)$customer->id;
		$this->context->cookie->customer_lastname	= $customer->lastname;
		$this->context->cookie->customer_firstname	= $customer->firstname;
		$this->context->cookie->passwd				= $customer->passwd;
		$this->context->cookie->email				= $customer->email;
		$this->context->cookie->id_cart				= (int)Cart::lastNoneOrderedCart((int)$customer->id);
		$this->context->cookie->is_guest			= $customer->isGuest();
		$this->context->cookie->logged				= 1;

		if (_PS_VERSION_ < '1.5')
		{
			Module::hookExec('authentication');
		}
		else
		{
			Hook::exec('authentication');
		}

		if ($redirect)
		{
			Tools::redirectLink(__PS_BASE_URI__.'order.php?step=1');
			exit(0);
		}
	}
}
