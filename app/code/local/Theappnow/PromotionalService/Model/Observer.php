<?php
/**
 * Update price observer
 */
class Theappnow_PromotionalService_Model_Observer extends Varien_Object{
//    public function beforeAdd($observer){
//        $params = Mage::app()->getFrontController()->getAction()->getRequest()->getParams();
//        $event = $observer->getEvent();
//        $quote_item = $event->getQuoteItem();
//
//        $options = $quote_item->getOptions();
//
//         foreach($options as $option){
//             var_dump($option);
//             var_dump($option->getPrice());
//             var_dump( $option->getTitle());
//             var_dump( $option->getValue());
//         }
//         die;
//        // var_dump($quote_item->getOriginalPrice());die;
//
//        // $quote_item->setOriginalCustomPrice(30);
//        // $quote_item->save();
//
//        return $this;
//    }

    public function checkoutAddAfter($observer){
        $repeatWorkTitle = Mage::getStoreConfig('promotionalservice/general/repeatwork_text');
        $event = $observer->getEvent();
        $item = $event->getQuoteItem();
        $product = $item->getProduct();
        $productOptions = $product
            // ->getTypeInstance(true)
            ->getOrderOptions($product);
        $product = Mage::getModel("catalog/product")->load($product->getId()); //product id 1

        $tier_price = $product->getResource()->getAttribute('tier_price')
            ->getFrontend()->getValue($product);
        $customprice = 0;
        foreach ($tier_price as $key => $value)
        {
            if ($item->getQty() >= $value['price_qty'] )
            {
                $customprice = (float)$value['price'];
            }

        }

        // if qty < min qty on first level tierprice

        if ($customprice == 0)
        {
            $customprice = $tier_price[0]['price'];
        }
        // if product without tierprice
        if (!$tier_price)
        {
            $customprice = $product->getPrice();
        }
        // var_dump($item->getQty());die;
        $session = Mage::getSingleton('checkout/session');
        // Loop through all items in the cart
        foreach ($session->getQuote()->getAllItems() as $item)
        {
            $options = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
            // var_dump($options);die;
        }
        if (isset($options['options']))
        {
            $options = $options['info_buyRequest'];
        }


        $servicePrice = 0;

        foreach ($options['options'] as $key => $value) {
//            foreach($item->getBuyRequest()->getOptions() as $code => $option){
//                if($option['value']== 'New logo artwork setup'){
//
//                    $foundOption = true;
//                    break;
//                }
//            }
//

            foreach ($product->getOptions() as $o) {
                $values = $o->getValues();

                foreach ($values as $v) {
                    if (isset($value['option_value']) && $value['option_value'] == $v->getOptionTypeId()) {
                        if ($v->getprice() > 0)
                        {
                            $servicePrice = $v->getprice(); /* get price of custom option*/
                        }
                    }
                }
            }
        }
        if ($servicePrice == 0){
            foreach ($options['options'] as $key => $value) {
                if ($value['price'] > 0)
                {
                    $servicePrice = $value['price'];
                }
            }
        }
        // if repeatwork do not add service price

        if ($options['options'][1]['value'] !== $repeatWorkTitle)
        {
            $customprice += $servicePrice;

        }
        $item->setCustomPrice($customprice);
        // echo $product->getPrice();
        $item->setOriginalCustomPrice($customprice);
        $item->getProduct()->setIsSuperMode(true);
        //  $item->save();

        return $this;
    }

    public function addScreenToPayPal(Varien_Event_Observer  $observer){

        $paypalCart = $observer->getPaypalCart();
        $salesEntity      = $paypalCart->getSalesEntity();
        Mage::log('Add screen to paypal', $paypalCart, $salesEntity);
        if ($paypalCart && $salesEntity) {

            $foundOption = false;
            foreach ($salesEntity->getAllItems() as $item) {
                $options = $item->getBuyRequest()->getOptions();
                if(!$foundOption)
                    foreach($options as $code => $option){
                        if($option['value']== 'New logo artwork setup'){

                            $foundOption = true;
                            break;
                        }
                    }
                else break;
            }

            if ($foundOption) {
                $amount = Theappnow_PromotionalService_Helper_Data::INSURANCE_FEE;
                $paypalCart->addItem(Mage::helper('promotionalservice')->__('New logo artwork setup'), 1, $amount, 'newartwork');

            }

        }
        return $this;
    }

    public function gatherData($observer)
    {   // $payment_info from $_POST global variable at this point.
        $data = $observer->getEvent()->getOrder();
        Mage::log($data->debug(), null, "gatherData.log", true);
    }
}