<?php

/**
 * Project name 05.Development
 * Created by:
 * User: Hoang Van Cong
 * Company: Theappnow.com
 * Email: conghv@theappnow.com
 * Date: 7/29/2017
 * Time: 10:49 AM
 */
class Theappnow_PromotionalService_Block_Grandtotal extends Mage_Tax_Block_Checkout_Grandtotal
{
    public function getTotalExclTax()
    {
        $address =  $this->getTotal()->getAddress();
        $excl = $address->getGrandTotal()-$this->getTotal()->getAddress()->getTaxAmount() + $address->getNewLogoArtworkSetup();
        $excl = max($excl, 0);
        return $excl;
    }
}