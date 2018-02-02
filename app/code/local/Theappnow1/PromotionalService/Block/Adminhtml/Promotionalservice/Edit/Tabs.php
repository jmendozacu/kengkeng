<?php
class Theappnow_PromotionalService_Block_Adminhtml_Promotionalservice_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("promotionalservice_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("promotionalservice")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("promotionalservice")->__("Item Information"),
				"title" => Mage::helper("promotionalservice")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("promotionalservice/adminhtml_promotionalservice_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
