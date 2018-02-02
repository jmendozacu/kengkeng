<?php

/**
 * Project name 05.Development
 * Created by:
 * User: Hoang Van Cong
 * Company: Theappnow.com
 * Email: conghv@theappnow.com
 * Date: 7/18/2017
 * Time: 5:14 PM
 */
class Theappnow_PromotionalService_Adminhtml_Block_Sales_Order_Totals extends Mage_Adminhtml_Block_Sales_Order_Totals
{
    /**
     * Initialize order totals array
     *
     * @return Mage_Sales_Block_Order_Totals
     */
    protected function _initTotals()
    {
        parent::_initTotals();
//        $amount = Theappnow_PromotionalService_Helper_Data::INSURANCE_FEE;
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