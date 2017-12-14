<?php
class ABSoft_StoreListing_Model_Mysql4_Storelocation extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("storelisting/storelocation", "store_location_id");
    }
}