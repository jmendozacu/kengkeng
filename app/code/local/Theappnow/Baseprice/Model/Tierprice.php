<?php

class Theappnow_Baseprice_Model_Tierprice extends Mage_Core_Model_Abstract
{
    public function _construct(){
        parent::_construct();
        $this->_init('baseprice/tierprice');
    }
}
