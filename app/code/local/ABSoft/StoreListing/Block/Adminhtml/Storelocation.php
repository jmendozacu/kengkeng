<?php


class ABSoft_StoreListing_Block_Adminhtml_Storelocation extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_storelocation";
	$this->_blockGroup = "storelisting";
	$this->_headerText = Mage::helper("storelisting")->__("Storelocation Manager");
	$this->_addButtonLabel = Mage::helper("storelisting")->__("Add New Item");
	parent::__construct();
	
	}

}