<?php
	
class ABSoft_StoreListing_Block_Adminhtml_Storelocation_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "store_location_id";
				$this->_blockGroup = "storelisting";
				$this->_controller = "adminhtml_storelocation";
				$this->_updateButton("save", "label", Mage::helper("storelisting")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("storelisting")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("storelisting")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);

				$this->_formScripts[] = "
							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("storelocation_data") && Mage::registry("storelocation_data")->getId() ){

				    return Mage::helper("storelisting")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("storelocation_data")->getId()));

				}
				else{

				     return Mage::helper("storelisting")->__("Add Item");

				}
		}
}