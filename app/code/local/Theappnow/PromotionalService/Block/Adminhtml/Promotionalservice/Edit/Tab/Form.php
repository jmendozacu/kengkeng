<?php
class Theappnow_PromotionalService_Block_Adminhtml_Promotionalservice_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("promotionalservice_form", array("legend"=>Mage::helper("promotionalservice")->__("Item information")));

				
						$fieldset->addField("description", "text", array(
						"label" => Mage::helper("promotionalservice")->__("Description"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "description",
						));
					
						$fieldset->addField("price", "text", array(
						"label" => Mage::helper("promotionalservice")->__("Price"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "price",
						));
					
						$fieldset->addField("ordering", "text", array(
						"label" => Mage::helper("promotionalservice")->__("Ordering"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "ordering",
						));
					

				if (Mage::getSingleton("adminhtml/session")->getPromotionalserviceData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getPromotionalserviceData());
					Mage::getSingleton("adminhtml/session")->setPromotionalserviceData(null);
				} 
				elseif(Mage::registry("promotionalservice_data")) {
				    $form->setValues(Mage::registry("promotionalservice_data")->getData());
				}
				return parent::_prepareForm();
		}
}
