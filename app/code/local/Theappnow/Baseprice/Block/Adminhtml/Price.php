<?php
class Theappnow_Baseprice_Block_Adminhtml_Price extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_price';
    $this->_blockGroup = 'baseprice';
    $this->_headerText = Mage::helper('baseprice')->__('Baseprice Manager');
    $this->_updateButton('add', 'label', Mage::helper('baseprice')->__('Add Baseprice'));
    parent::__construct();
  }
}
?>