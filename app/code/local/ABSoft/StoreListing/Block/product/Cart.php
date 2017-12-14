<?php

class ABSoft_StoreListing_Block_Product_Cart extends Mage_Core_Block_Template
{
    public function getProduct($id)
    {
        $product = Mage::getModel('catalog/product')->load($id);
        return $product;
    }

    public function getCart()
    {
        $quote = Mage::getSingleton('checkout/cart')->getQuote();
        return $quote;
    }

    public function getProductInCart()
    {
        $products = Mage::getSingleton('checkout/cart')->getQuote()->getAllItems();
        return $products;
    }

    public function getRemoveUrl($product_id)
    {
        $quote = Mage::getModel('checkout/session')->getQuote();
        $product = $product = Mage::getModel('catalog/product')->load($product_id);

        $itemId = 0;
        $item = $quote->getItemByProduct($product);
        foreach ($quote->getAllItems() as $item) {
            if ($item->getProductId() == $product_id) {
                return $this->getUrl(
                    'checkout/cart/delete',
                    array(
                        'id' => $item->getItemId(),
                        'form_key' => Mage::getSingleton('core/session')->getFormKey(),
                        Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED => $this->helper('core/url')->getEncodedUrl()
                    )
                );
            }

        }
    }

    public function getOptionsChecked($item_id)
    {
        $cart = Mage::getSingleton('checkout/cart')->getQuote()->getAllItems();

        $optionsCart = [];

        foreach ($cart as $item) {
            if ($item->getItemId() == $item_id) {
                foreach ($item->getBuyRequest()->getOptions() as $option) {
                    $optionsCart = array_merge($optionsCart, $option);
                }
                break;
            }
        }
        return implode('-',$optionsCart);
    }
    public function getNameProductIncart($item_id){
        $title = '';
        $product = Mage::getSingleton('checkout/cart')->getQuote()->getAllItems();
        foreach ($product as $item) {
            if($item->getItemId()==$item_id){
                $title.= $item->getName();
                $options = Mage::helper('catalog/product_configuration')->getCustomOptions($item);
                foreach($options as  $option){
                    $title.=",".$option['print_value'];
                }
            }

        }
        return $title;
    }


}