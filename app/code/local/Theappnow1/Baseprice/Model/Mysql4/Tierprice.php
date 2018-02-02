<?php

class Theappnow_Baseprice_Model_Mysql4_Tierprice extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
    {
        $this->_init('baseprice/tierprice', 'id');
    }
}