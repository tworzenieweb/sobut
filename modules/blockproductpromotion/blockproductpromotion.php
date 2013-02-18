<?php

if (!defined('_CAN_LOAD_FILES_'))
	exit;
	
class BlockProductPromotion extends Module
{
	public $productId;

	function __construct()
	{
		$this->name = 'blockproductpromotion';
		$this->tab = 'Blocks';
		$this->version = 0.1;

		parent::__construct();

		$this->displayName = $this->l('Block product promotion');
		$this->description = $this->l('Adds a block to display a product');

		$this->productId = htmlentities(Configuration::get('BLOCK_PRODUCT_PROMOTION'), ENT_QUOTES, 'UTF-8');
	}


	function install()
	{
		if (!parent::install())
			return false;
		if (!$this->registerHook('rightColumn') OR !$this->registerHook('leftColumn'))
			return false;
		return true;
	}

	public function postProcess()
	{
		global $currentIndex;

		$errors = false;
		if (Tools::isSubmit('submitProductPromotionConf'))
		{
			if ($productId = Tools::getValue('product_id'))
			{
				Configuration::updateValue('BLOCK_PRODUCT_PROMOTION', $productId);
				$this->productId = htmlentities($productId, ENT_QUOTES, 'UTF-8');
			}
		}
		if ($errors)
			echo $this->displayError($errors);
	}

	public function getContent()
	{
		$this->postProcess();
		echo '
<form action="'.$_SERVER['REQUEST_URI'].'" method="post" enctype="multipart/form-data">
<fieldset><legend>'.$this->l('Product promotion block configuration').'</legend>
<br/>
<br/>
<label for="product_id">'.$this->l('Product id').'&nbsp;</label><input id="product_id" type="text" name="product_id" value="'.$this->productId.'" />
<br class="clear"/>
<br/>
<input class="button" type="submit" name="submitProductPromotionConf" value="'.$this->l('validate').'" style="margin-left: 200px;"/>
</fieldset>
</form>
';
	}

	/**
	* Returns module content
	*
	* @param array $params Parameters
	* @return string Content
	*/
	function hookRightColumn($params)
	{
		global $smarty;

		if ($this->productId)
		{
			$defaultCover = Language::getIsoById($params['cookie']->id_lang).'-default';
            
			$products = Db::getInstance()->ExecuteS('
			SELECT i.id_image, p.id_product, il.legend, p.active, pl.name, pl.description_short, pl.link_rewrite, cl.link_rewrite AS category_rewrite
			FROM '._DB_PREFIX_.'product p
			LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (pl.id_product = p.id_product)
			LEFT JOIN '._DB_PREFIX_.'image i ON (i.id_product = p.id_product)
			LEFT JOIN '._DB_PREFIX_.'image_lang il ON (il.id_image = i.id_image)
			LEFT JOIN '._DB_PREFIX_.'category_lang cl ON (cl.id_category = p.id_category_default)
			WHERE p.id_product = '.$this->productId.'
			AND pl.id_lang = '.intval($params['cookie']->id_lang).'
			AND cl.id_lang = '.intval($params['cookie']->id_lang).'
			AND i.cover = 1'
			);

            if ($products) {

                $product = $products[0];

				$obj = (object)'Product';
                
                $obj->id = intval($product['id_product']);
                $obj->cover = intval($product['id_product']).'-'.intval($product['id_image']);
                $obj->legend = $product['legend'];
                $obj->name = $product['name'];
                $obj->description_short = strip_tags($product['description_short']);
                $obj->link_rewrite = $product['link_rewrite'];
                $obj->category_rewrite = $product['category_rewrite'];

                $smarty->assign(array( 'product' => $obj ));

                return $this->display(__FILE__, 'blockproductpromotion.tpl');
            }
		}

        return ;
	}

	function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}

}

?>
