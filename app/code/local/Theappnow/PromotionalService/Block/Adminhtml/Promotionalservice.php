<?php


class Theappnow_PromotionalService_Block_Adminhtml_Promotionalservice extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_promotionalservice";
	$this->_blockGroup = "promotionalservice";
	$this->_headerText = Mage::helper("promotionalservice")->__("Promotionalservice Manager");
	$this->_addButtonLabel = Mage::helper("promotionalservice")->__("Add New Item");
	parent::__construct();
	
	}

}