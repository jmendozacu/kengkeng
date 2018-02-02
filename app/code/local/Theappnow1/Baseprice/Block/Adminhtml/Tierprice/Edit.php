<?php

class Theappnow_Baseprice_Block_Adminhtml_Tierprice_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'baseprice';
        $this->_controller = 'adminhtml_tierprice';

        $this->_updateButton('save', 'label', Mage::helper('baseprice')->__('Save tierprice'));
        $this->_updateButton('delete', 'label', Mage::helper('baseprice')->__('Delete tierprice'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('baseprice_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'baseprice_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'baseprice_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('baseprice_tierpricedata') && Mage::registry('baseprice_tierpricedata')->getId()) {
            return Mage::helper('baseprice')->__("Edit Request #%s", $this->htmlEscape(Mage::registry('baseprice_tierpricedata')->getId()));
        } else {
            return Mage::helper('baseprice')->__('Add Item');
        }
    }
}
?>