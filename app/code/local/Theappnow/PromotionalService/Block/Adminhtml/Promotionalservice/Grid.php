<?php

class Theappnow_PromotionalService_Block_Adminhtml_Promotionalservice_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("promotionalserviceGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("promotionalservice/promotionalservice")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("id", array(
				"header" => Mage::helper("promotionalservice")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "id",
				));
                
				$this->addColumn("description", array(
				"header" => Mage::helper("promotionalservice")->__("Description"),
				"index" => "description",
				));
				$this->addColumn("price", array(
				"header" => Mage::helper("promotionalservice")->__("Price"),
				"index" => "price",
				));
				$this->addColumn("ordering", array(
				"header" => Mage::helper("promotionalservice")->__("Ordering"),
				"index" => "ordering",
				));
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_promotionalservice', array(
					 'label'=> Mage::helper('promotionalservice')->__('Remove'),
					 'url'  => $this->getUrl('*/adminhtml_promotionalservice/massRemove'),
					 'confirm' => Mage::helper('promotionalservice')->__('Are you sure?')
				));
			return $this;
		}
			

}