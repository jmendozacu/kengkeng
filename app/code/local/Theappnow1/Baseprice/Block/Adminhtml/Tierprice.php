<?php
class Theappnow_Baseprice_Block_Adminhtml_Tierprice extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_tierprice';
    $this->_blockGroup = 'baseprice';
    $this->_headerText = Mage::helper('baseprice')->__('Tierprice Manager');
    $this->_updateButton('add', 'label', Mage::helper('baseprice')->__('Add Tierprice'));
    parent::__construct();
  }
}
?>