<?php

class Theappnow_Baseprice_Adminhtml_TierpriceController extends Mage_Adminhtml_Controller_Action
{
    /**
     * index action
     */
    public function indexAction()
    {


//var_dump(Mage::app()->getLayout()->getUpdate()->getHandles());die;

        $this->loadLayout()
            ->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $basepriceId = $this->getRequest()->getParam('id');
        $model = Mage::getModel('baseprice/tierprice')->load($basepriceId);

        if ($model->getId() || $basepriceId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('baseprice_tierpricedata', $model);

            $this->loadLayout();
            $this->_setActiveMenu('baseprice/baseprice');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Baseprice'),
                Mage::helper('adminhtml')->__('Tierprice')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('baseprice/adminhtml_tierprice_edit'))
                ->_addLeft($this->getLayout()->createBlock('baseprice/adminhtml_tierprice_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('baseprice')->__('Tierprice does not exist')
            );
            $this->_redirect('*/*/');
        }
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('baseprice/tierprice');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));

            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('baseprice')->__('Tierprice was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
    }

}