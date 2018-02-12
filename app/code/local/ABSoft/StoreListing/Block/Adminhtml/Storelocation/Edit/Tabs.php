<?php
class ABSoft_StoreListing_Block_Adminhtml_Storelocation_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("storelocation_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("storelisting")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("storelisting")->__("Item Information"),
				"title" => Mage::helper("storelisting")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("storelisting/adminhtml_storelocation_edit_tab_form")->toHtml(),
				));

				return parent::_beforeToHtml();
		}

}
