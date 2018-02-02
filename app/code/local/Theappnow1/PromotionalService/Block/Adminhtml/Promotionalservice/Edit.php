<?php
	
class Theappnow_PromotionalService_Block_Adminhtml_Promotionalservice_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "promotionalservice";
				$this->_controller = "adminhtml_promotionalservice";
				$this->_updateButton("save", "label", Mage::helper("promotionalservice")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("promotionalservice")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("promotionalservice")->__("Save And Continue Edit"),
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
				if( Mage::registry("promotionalservice_data") && Mage::registry("promotionalservice_data")->getId() ){

				    return Mage::helper("promotionalservice")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("promotionalservice_data")->getId()));

				} 
				else{

				     return Mage::helper("promotionalservice")->__("Add Item");

				}
		}
}