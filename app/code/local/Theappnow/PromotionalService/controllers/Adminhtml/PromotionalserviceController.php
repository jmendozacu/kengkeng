<?php

class Theappnow_PromotionalService_Adminhtml_PromotionalserviceController extends Mage_Adminhtml_Controller_Action
{
		protected function _isAllowed()
		{
		//return Mage::getSingleton('admin/session')->isAllowed('promotionalservice/promotionalservice');
			return true;
		}

		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("promotionalservice/promotionalservice")->_addBreadcrumb(Mage::helper("adminhtml")->__("Promotionalservice  Manager"),Mage::helper("adminhtml")->__("Promotionalservice Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("PromotionalService"));
			    $this->_title($this->__("Manage"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("PromotionalService"));
				$this->_title($this->__("Promotionalservice"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("promotionalservice/promotionalservice")->load($id);
				if ($model->getId()) {
					Mage::register("promotionalservice_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("promotionalservice/promotionalservice");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Promotionalservice Manager"), Mage::helper("adminhtml")->__("Promotionalservice Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Promotionalservice Description"), Mage::helper("adminhtml")->__("Promotionalservice Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("promotionalservice/adminhtml_promotionalservice_edit"))->_addLeft($this->getLayout()->createBlock("promotionalservice/adminhtml_promotionalservice_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("promotionalservice")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("PromotionalService"));
		$this->_title($this->__("Promotionalservice"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("promotionalservice/promotionalservice")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("promotionalservice_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("promotionalservice/promotionalservice");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Promotionalservice Manager"), Mage::helper("adminhtml")->__("Promotionalservice Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Promotionalservice Description"), Mage::helper("adminhtml")->__("Promotionalservice Description"));


		$this->_addContent($this->getLayout()->createBlock("promotionalservice/adminhtml_promotionalservice_edit"))->_addLeft($this->getLayout()->createBlock("promotionalservice/adminhtml_promotionalservice_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						

						$model = Mage::getModel("promotionalservice/promotionalservice")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Promotionalservice was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setPromotionalserviceData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setPromotionalserviceData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("promotionalservice/promotionalservice");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("promotionalservice/promotionalservice");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'promotionalservice.csv';
			$grid       = $this->getLayout()->createBlock('promotionalservice/adminhtml_promotionalservice_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'promotionalservice.xml';
			$grid       = $this->getLayout()->createBlock('promotionalservice/adminhtml_promotionalservice_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
