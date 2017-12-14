<?php
class ABSoft_StoreListing_Block_Product extends Mage_Core_Block_Template
{
    public function getProduct($id){
        $product = Mage::getModel('catalog/product')->load($id);
        return $product;
    }
    public function getCart(){
        $quote = Mage::getSingleton('checkout/cart')->getQuote();
        return $quote;
    }
    public function getProductInCart(){
        $products = Mage::getSingleton('checkout/cart')->getQuote()->getAllItems();
        return $products;
    }
    public function getCategorys(){

        $root_category_id =  Mage::app()->getStore()->getRootCategoryId();
        $categorys = Mage::getModel('catalog/category')->getCollection()
            ->addAttributeToFilter('parent_id',$root_category_id)
            ->addAttributeToFilter('level',2)
            ->addAttributeToFilter('is_active', 1)
            ->addAttributeToSelect("name")
            ->addAttributeToSelect("description")
            ->addAttributeToSelect('thumbnail');
        return $categorys;

    }
    public function getSubcategorys($category_id){
        $categorys = Mage::getModel('catalog/category')->getCollection()
            ->addAttributeToFilter('parent_id',$category_id)
            ->addAttributeToFilter('level',3)
            ->addAttributeToFilter('is_active', 1)
            ->addAttributeToSelect("description")
            ->addAttributeToSelect("name")
            ->addAttributeToSelect('thumbnail');
        return $categorys;
    }
    public function getProductByCategory(){
        if(Mage::registry('category_id')){
            $category_id = Mage::registry('category_id');
        } else {
            $rootId     = Mage::app()->getStore()->getRootCategoryId();
            $category_id =Mage::getModel('catalog/category')
                ->getCollection()
                ->addAttributeToFilter('parent_id',$rootId)
                ->addAttributeToFilter('is_active', 1)
                ->getFirstItem()
                ->getId();
        }
        $data = [];
        $data['category']=$category = Mage::getModel('catalog/category')->load($category_id);
        $data['products']=$products = Mage::getModel('catalog/product')
            ->getCollection()
            ->addCategoryFilter($category)
            ->addAttributeToSelect(['name', 'price']);
        return $data;
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
    public function getOptionsProduct($product_id){
            $options=[];
            $product = Mage::getModel('catalog/product')->load($product_id);
            foreach ($product->getOptions() as $index => $option) {
                $options[$index]= $option->getData();

                foreach ($option->getValues() as $value) {
                    $options[$index]['option_value'][]= $value->getData();
                }
            }
            return $options;
    }

}