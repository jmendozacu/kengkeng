<?php
class ABSoft_StoreListing_Adminhtml_StorelistingbackendController extends Mage_Adminhtml_Controller_Action
{

	protected function _isAllowed()
	{
		//return Mage::getSingleton('admin/session')->isAllowed('storelisting/storelistingbackend');
		return true;
	}

	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("ABSoft Store listing"));
	   $this->renderLayout();
    }
}