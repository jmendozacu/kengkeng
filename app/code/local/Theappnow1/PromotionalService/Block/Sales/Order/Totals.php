<?php

/**
 * Project name 05.Development
 * Created by:
 * User: Hoang Van Cong
 * Company: Theappnow.com
 * Email: conghv@theappnow.com
 * Date: 7/19/2017
 * Time: 3:05 PM
 */
class Theappnow_Promotionalservice_Block_Sales_Order_Totals extends  Mage_Core_Block_Abstract
{
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * Add this total to parent
     */
    public function initTotals()
    {
        Mage::log('Sale order total');
        if ((float)$this->getSource()->getNewLogoArtworkSetup() == 0) {
            return $this;
        }

        $total = new Varien_Object(array(
            'code'  => 'new_logo_artwork_setup',
            'field' => 'new_logo_artwork_setup',
            'value' => Theappnow_PromotionalService_Helper_Data::INSURANCE_FEE,
            'label' => $this->__('New logo artwork setup')
        ));

        $this->getParentBlock()->addTotalBefore($total, 'shipping');
        return $this;
    }
}