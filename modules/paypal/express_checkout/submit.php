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

include_once(dirname(__FILE__) . '/../../../config/config.inc.php');
include_once(dirname(__FILE__) . '/../../../init.php');

global $cart;

include_once(_PS_MODULE_DIR_ . 'paypal/express_checkout/paypal_express_checkout.php');

if (_PS_VERSION_ < '1.5')
{
	require_once(_PS_ROOT_DIR_ . '/controllers/OrderConfirmationController.php');
}

/* 1.4 Compatibility */
class PayPalExpressCheckoutSubmit extends OrderConfirmationControllerCore
{

	public function __construct()
	{
		$this->paypal = new PayPal();
		$this->context = Context::getContext();
		parent::__construct();
		$this->run();
	}

	public function displayContent()
	{
		$order = PayPalOrder::getOrderById((int)(Tools::getValue('id_order')));

		$this->context->smarty->assign(
			array(
				'order' => $order,
				'currency' => $this->context->currency
			)
		);

		echo $this->paypal->fetchTemplate('/views/templates/front/', 'order-confirmation');
	}
}

if (Tools::getValue('id_module') && Tools::getValue('key') && Tools::getValue('id_cart') && Tools::getValue('id_order'))
{
	if (_PS_VERSION_ < '1.5')
	{
		new PayPalExpressCheckoutSubmit();
	}
}
else
{
	$request_type	= Tools::getValue('express_checkout');
	$ppec			= new PaypalExpressCheckout($request_type);

	if ($request_type && $ppec->type)
	{
		$id_product 			= (int)Tools::getValue('id_product');
		$id_product_attribute	= (int)Tools::getValue('id_p_attr');
		$product_quantity		= (int)Tools::getValue('quantity');

		if ($id_product && $id_product_attribute && $product_quantity)
		{
            if ($ppec->context->cart->id == false)
            {
                // Create new Cart to avoid any refresh or other bad manipulations
                $ppec->context->cart = new Cart();

                $ppec->context->cart->id_currency	= (int)$ppec->context->currency->id;
                $ppec->context->cart->id_lang		= (int)$ppec->context->language->id;

                $secure_key = isset($ppec->context->customer) ? $ppec->context->customer->secure_key : '';
                $ppec->context->cart->secure_key	= $secure_key;

                // Customer settings
                $ppec->context->cart->id_guest		= (int)$ppec->context->cookie->id_guest;
                $ppec->context->cart->id_customer	= (int)$ppec->context->customer->id;

                if (!$ppec->context->cart->add())
				{
                    $ppec->logs[] = $ppec->l('Cannot create new cart');
				}
				else
				{
					$ppec->context->cookie->id_cart = (int)$ppec->context->cart->id;
				}
            }

			$ppec->context->cart->updateQty((int)$product_quantity, (int)$id_product, (int)$id_product_attribute);
			$ppec->context->cart->update();
		}

		// Set details for a payment
		$ppec->setExpressCheckout();
		if ($ppec->hasSucceedRequest() && !empty($ppec->token))
		{
			$ppec->redirectToAPI();
		}
		// Display Error and die with this method
		else
		{
			$ppec->displayPayPalAPIError($ppec->l('Error during the prepration of the express checkout payment'), $ppec->logs);
		}
	}
	// If a token exist with payer_id, then we are back from the PayPal API
	else if (!empty($ppec->token) && $ppec->token == Tools::getValue('token') && ($ppec->payer_id = Tools::getValue('PayerID')))
	{
		// Get payment infos from paypal
		$ppec->getExpressCheckout();

		if ($ppec->hasSucceedRequest() && !empty($ppec->token))
		{
            $address = null;
			$customer = null;

			// Create Customer if not exist with address etc
			if ($ppec->getContext()->cookie->logged)
			{
				if (!($id_customer = Paypal::getPayPalCustomerIdByEmail($ppec->result['EMAIL'])))
				{
					PayPal::addPayPalCustomer($ppec->getContext()->customer->id, $ppec->result['EMAIL']);
				}

				$customer = $ppec->getContext()->customer;
			}
			else if (($id_customer = Customer::customerExists($ppec->result['EMAIL'], true)))
			{
				$customer = new Customer($id_customer);
			}
			else
			{
				$customer				= new Customer();

				$customer->email		= $ppec->result['EMAIL'];
				$customer->lastname		= $ppec->result['LASTNAME'];
				$customer->firstname	= $ppec->result['FIRSTNAME'];
				$customer->passwd		= Tools::encrypt(Tools::passwdGen());

				$customer->add();

				PayPal::addPayPalCustomer($customer->id, $ppec->result['EMAIL']);
			}

			if (!$customer->id)
			{
				$ppec->logs[] = $ppec->l('Cannot create customer');
			}

			foreach ($customer->getAddresses($ppec->getContext()->language->id) as $address)
			{
				if ($address['alias'] == 'Paypal_Address')
				{
					$address = new Address($address['id_address']);
					break;
				}
			}

			// Create address
			if ((!$address || !$address->id) && $customer->id)
			{
				$address				= new Address();

				$address->id_country	= Country::getByIso($ppec->result['COUNTRYCODE']);
				$address->alias			= 'Paypal_Address';
				$address->lastname		= $customer->lastname;
				$address->firstname		= $customer->firstname;
				$address->address1		= $ppec->result['PAYMENTREQUEST_0_SHIPTOSTREET'];
				$address->city			= $ppec->result['PAYMENTREQUEST_0_SHIPTOCITY'];
				$address->postcode		= $ppec->result['SHIPTOZIP'];
				$address->id_customer	= $customer->id;

				$address->add();
			}

			if ($customer->id && !$address->id)
			{
				$ppec->logs[] = $ppec->l('Cannot create Address');
			}

			// Create Order
			if ($address->id && $customer->id)
			{
				$ppec->getContext()->cart->id_customer = $customer->id;
				$ppec->getContext()->cart->id_guest = $ppec->getContext()->cookie->id_guest;

				if (!$ppec->getContext()->cart->update())
				{
					$ppec->logs[] = $ppec->l('Cannot update existing cart');
				}
				else
				{
					$ppec->redirectToCheckout($customer, ($ppec->type != 'payment_cart'));
				}
			}
		}
	}

	// If Previous steps succeed, ready (means 'ready to pay') will be set to true
	if (($ppec->ready && !empty($ppec->token) && (Tools::isSubmit('confirmation') || $ppec->type == 'payment_cart')))
	{
		// Check modification on the product cart / quantity
		if ($ppec->isProductsListStillRight())
		{
			$order		= null;
			$cart		= $ppec->getContext()->cart;
			$customer	= new Customer((int)$cart->id_customer);

			// When all information are checked before, we can validate the payment to paypal
			// and create the prestashop order

			$ppec->doExpressCheckout();

			/// Check payment (real paid))
			if ($ppec->hasSucceedRequest() && !empty($ppec->token) && $ppec->rightPaymentProcess())
			{
				$transaction	= array(
									'id_transaction' => pSQL($ppec->result['PAYMENTINFO_0_TRANSACTIONID']),
				                   	'id_invoice'     => null,
				                   	'currency'       => pSQL($ppec->result['PAYMENTINFO_0_CURRENCYCODE']),
				                   	'total_paid'     => (float)$ppec->result['PAYMENTINFO_0_AMT'],
				                   	'shipping'       => (float)$ppec->result['PAYMENTREQUEST_0_SHIPPINGAMT'],
				                   	'payment_date'   => pSQL($ppec->result['PAYMENTINFO_0_ORDERTIME'])
				);

				$payment_type	= (int)Configuration::get('PS_OS_PAYMENT');
				$message		= $ppec->l('Payment accepted.').'<br />';;
			}
			else
			{
				$transaction 	= array();
				$payment_type	= (int)Configuration::get('PS_OS_ERROR');
				$message		= $ppec->l('Price payed on paypal is not the same that on PrestaShop.').'<br />';
			}

			if (_PS_VERSION_ >= '1.5')
			{
				$shop									= $ppec->getContext()->shop;
				$ppec->getContext()->cookie->id_cart	= $cart->id;

				$ppec->validateOrder($cart->id, $payment_type, $cart->getOrderTotal(true, Cart::BOTH), 'PayPal', $message, $transaction, (int)$cart->id_currency, false, $customer->secure_key, $shop);
			}
			else
			{
				$ppec->validateOrder($cart->id, $payment_type, $cart->getOrderTotal(true, Cart::BOTH), 'PayPal', $message, $transaction, (int)$cart->id_currency, false, $customer->secure_key);
			}

			if (!$ppec->currentOrder)
			{
				$ppec->logs[] = $this->l('Cannot create order');
			}
			else
			{
				$id_order 			= (int)$ppec->currentOrder;
				$order				= new Order($id_order);
				$order->total_paid	= $ppec->getTotalPaid();
			}

			unset(Context::getContext()->cookie->{PaypalExpressCheckout::$COOKIE_NAME});

			// Update for the Paypal shipping cost
			if ($order)
			{
				$order->update();

				$values = array('key'       => $customer->secure_key,
				                'id_module' => (int)$ppec->id,
				                'id_cart'   => (int)$cart->id,
				                'id_order'  => (int)$ppec->currentOrder);

				$query = http_build_query($values, '', '&');

				if (_PS_VERSION_ < '1.5')
				{
					Tools::redirectLink(__PS_BASE_URI__ . '/modules/paypal/express_checkout/submit.php?' . $query);
				}
				else
				{
					$controller = new FrontController();
					$controller->init();

					Tools::redirect(Context::getContext()->link->getModuleLink('paypal', 'submit', $values));
				}
			}
		}
		else
		{
			// If Cart changed, no need to keep the paypal data
			unset(Context::getContext()->cookie->{PaypalExpressCheckout::$COOKIE_NAME});
			$ppec->logs[] = $ppec->l('Cart changed since the last checkout express, please make a new Paypal checkout payment');
		}
	}

	$display = new BWDisplay();
	$shop_url = PayPal::getShopDomainSsl(true, true);

	// Display payment confirmation
	if ($ppec->ready && Tools::getValue('get_confirmation'))
	{
		$ppec->getContext()->smarty->assign(
			array(
                'form_action'	=> $shop_url . _MODULE_DIR_ . $ppec->name . '/express_checkout/submit.php',
				'currency'    => new Currency((int)$ppec->getContext()->cart->id_currency),
				'total'       => $ppec->getContext()->cart->getOrderTotal(true),
				'logos'       => $ppec->paypal_logos->getLogos(),
			)
		);

		$display->setTemplate(_PS_MODULE_DIR_.'paypal/views/templates/front/express_checkout/payment.tpl');
	}
	// Display result if error occurred
	else
	{
		$ppec->getContext()->smarty->assign(
			array(
				'message' => $ppec->l('Error occurred:'),
		        'logs'    => $ppec->logs
			)
		);

		$display->setTemplate(_PS_MODULE_DIR_.'paypal/views/templates/front/error.tpl');
	}

	$display->run();
}