<?php
class ABSoft_StoreListing_Helper_Data extends Mage_Core_Helper_Abstract
{
        public function getItemIdOptions($options){
            $product = Mage::getSingleton('checkout/cart')->getQuote()->getAllItems();
            foreach ($product as $item) {
                $count=0;
                foreach($item->getBuyRequest()->getOptions() as $code => $option){
                    foreach ($option as $value) {
                        if(in_array($value,$options)==1)
                            $count++;
                    }
                }
                if($count==count($options))
                    return $item->getId();
            }
            return false;
        }
}
	 