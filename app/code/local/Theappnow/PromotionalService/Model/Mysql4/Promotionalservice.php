<?php
class Theappnow_PromotionalService_Model_Mysql4_Promotionalservice extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("promotionalservice/promotionalservice", "id");
    }
}