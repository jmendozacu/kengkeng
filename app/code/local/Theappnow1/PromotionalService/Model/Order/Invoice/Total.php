<?php

/**
 * Project name 05.Development
 * Created by:
 * User: Hoang Van Cong
 * Company: Theappnow.com
 * Email: conghv@theappnow.com
 * Date: 7/18/2017
 * Time: 5:05 PM
 */
class Theappnow_PromotionalService_Model_Order_Invoice_Total extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        Mage::log('Order invoice total ');
        $order = $invoice->getOrder();
        $items = $order->getItemsCollection();
        $foundOption = false;
        foreach($items as $item){
            if(!$foundOption)
                //not found new artwork, continue search
                foreach($item->getBuyRequest()->getOptions() as $code => $option){
                    if($option['value']== 'New logo artwork setup'){

                        $foundOption = true;
                        break;
                    }
                }
            else break;
        }
        if($foundOption) {
            $amount = Theappnow_PromotionalService_Helper_Data::INSURANCE_FEE;
            $invoice->setNewLogoArtworkSetup($amount);
            $invoice->setBaseNewLogoArtworkSetup($amount);
            $invoice->setGrandTotal($invoice->getGrandTotal() + $amount);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $amount);
        }
        return $this;
    }
}