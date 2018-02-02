<?php

class Theappnow_Baseprice_Model_Mysql4_Price extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
    {
        $this->_init('baseprice/price', 'baseprice_id');
    }
}