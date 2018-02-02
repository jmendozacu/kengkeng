<?php

/**
 * Project name 05.Development
 * Created by:
 * User: Hoang Van Cong
 * Company: Theappnow.com
 * Email: conghv@theappnow.com
 * Date: 7/18/2017
 * Time: 11:57 AM
 */
class Theappnow_PromotionalService_Model_Quote_Address_Total extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    public function __construct()
    {
        $this->setCode('new_logo_artwork_setup');
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return Mage::helper('promotionalservice')->__('New logo artwork setup');
    }

    /**
     * Collect totals information about artwork service, add to total bill
     *
     * @param   Mage_Sales_Model_Quote_Address $address
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {

        parent::collect($address);
        if (($address->getAddressType() == 'billing')) {
            return $this;
        }

        Mage::log('Quote address total collect');
        $amount = Theappnow_PromotionalService_Helper_Data::INSURANCE_FEE;
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $cart = $quote->getAllVisibleItems();

        $foundOption = false;
        foreach($cart as $item){
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

        if($foundOption){

            $this->_addAmount($amount);
            $this->_addBaseAmount($amount);

            $address->setNewLogoArtworkSetup($amount);
            $address->setBaseNewLogoArtworkSetup($amount);
        }


        return $this;
    }

    /**
     * Display special service  totals information to address object
     *
     * @param   Mage_Sales_Model_Quote_Address $address
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {

//        if($address->getAddressType() == 'billing'){
            Mage::log('Fetch billing total', $address->getAddressType());
            $amount = $address->getNewLogoArtworkSetup();
            if($amount != 0)
            $address->addTotal(array(
                'code'  => $this->getCode(),
                'title' => $this->getLabel(),
                'value' => $amount
            ));
//        }


        return $this;
    }
}