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
class Theappnow_PromotionalService_Model_Order_Creditmemo_Total extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        Mage::log('Credit demo total');

        $order = $creditmemo->getOrder();
        $_items = $order->getAllItems();
        $foundOption = false;

        foreach($_items as $item){
            if(!$foundOption) {
                foreach($item->getBuyRequest()->getOptions() as $code => $option){
                    if($option['value']== 'New logo artwork setup'){

                        $foundOption = true;
                        break;
                    }
                }

            }
            else break;
        }

        if ($foundOption) {
            $amount = Theappnow_PromotionalService_Helper_Data::INSURANCE_FEE;
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $amount);
            $creditmemo->setBaseNewLogoArtworkSetup($amount);
            $creditmemo->setNewLogoArtworkSetup($amount);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $amount);

        }

        return $this;
    }
}