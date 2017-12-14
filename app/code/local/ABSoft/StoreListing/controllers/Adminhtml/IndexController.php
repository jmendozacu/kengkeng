<?php
class ABSoft_StoreListing_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
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
    public function getAdditionalAction()
    {
        $this->getResponse()->setBody($this->_getAdditionalHtml());
    }
    protected function _getAdditionalHtml()
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_additional');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();
        Mage::getSingleton('core/translate_inline')->processResponseBody($output);
        return $output;
    }
}