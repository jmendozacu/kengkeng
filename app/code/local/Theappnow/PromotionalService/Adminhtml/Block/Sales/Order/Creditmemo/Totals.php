<?php

/**
 * Project name 05.Development
 * Created by:
 * User: Hoang Van Cong
 * Company: Theappnow.com
 * Email: conghv@theappnow.com
 * Date: 7/18/2017
 * Time: 5:15 PM
 */
class Theappnow_PromotionalService_Adminhtml_Block_Sales_Order_Creditmemo_Totals extends Mage_Adminhtml_Block_Sales_Order_Creditmemo_Totals
{
    /**
     * Initialize order totals array
     *
     * @return Mage_Sales_Block_Order_Totals
     */
    protected function _initTotals()
    {
        parent::_initTotals();

        $foundOption = false;

        $_items = $this->getOrder()->getAllItems();

        foreach($_items as $item){
            if(!$foundOption) {
//                $options = $item->getOrderItem()->getProductOptions();
//                if(array_key_exists('info_buyRequest', $options) && array_key_exists('options', $options['info_buyRequest'])){
//                    $requestOptions = $options['info_buyRequest']['options'];
//                    foreach($requestOptions as $opt) {
//                        if ($opt['value'] == 'New logo artwork setup') {
//                            $foundOption = true;
//                            break;
//                        }
//                    }
//                }
                foreach($item->getBuyRequest()->getOptions() as $code => $option){
                    if($option['value']== 'New logo artwork setup'){

                        $foundOption = true;
                        break;
                    }
                }

            }
            else break;
        }

        $amount = Theappnow_PromotionalService_Helper_Data::INSURANCE_FEE;
        if ($foundOption) {
            $this->addTotalBefore(new Varien_Object(array(
                'code'      => 'promotional_service_quote',
                'value'     => $amount,
                'base_value'=> $amount,
                'label'     => $this->helper('promotionalservice')->__('New logo artwork setup'),
            ), array('shipping', 'tax')));
        }


//
//        if ($amount) {
//            $this->addTotalBefore(new Varien_Object(array(
//                'code'      => 'promotional_service_quote',
//                'value'     => $amount,
//                'base_value'=> $amount,
//                'label'     => $this->helper('promotionalservice')->__('New logo artwork setup'),
//            ), array('shipping', 'tax')));
//        }

        return $this;
    }
}