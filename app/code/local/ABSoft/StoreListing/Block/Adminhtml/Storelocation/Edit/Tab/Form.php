<?php

class ABSoft_StoreListing_Block_Adminhtml_Storelocation_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {

        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset("storelisting_form", array("legend" => Mage::helper("storelisting")->__("Item information")));
        $stores = array();
            $current_id = Mage::app()->getRequest()->getParam("id");
            if($current_id){
            $store_locations = Mage::getModel('storelisting/storelocation')->getCollection()->addFieldToFilter('store_location_id', $current_id);
            $store_locations->getSelect()->join(['core_store' => $store_locations->getTable('core/store')],
                'main_table.store_id = core_store.store_id',
                ["core_store.name as core_store_name", "main_table.name as name_of_main_table"]
            );
            $data = $store_locations->getFirstItem();
            $stores[$data->getStoreId()] = $data->getCoreStoreName();;


        } else {
            $stores = ABSoft_StoreListing_Block_Adminhtml_Storelocation_Grid::getStores();
        }
            $data_form = Mage::registry("storelocation_data");
        $fieldset->addField('store_id', 'select', array(
            'label' => Mage::helper('storelisting')->__('Store'),
            'values' => $stores,
            'name' => 'store_id',
            'onclick' => "",
            'onchange' => "",
            'disabled' => false,
            'readonly' => false,
            'tabindex' => 1
        ));
        $fieldset->addField("address", "text", array(
            "label" => Mage::helper("storelisting")->__("Address"),
            "name" => "address",
        ));
        $fieldset->addField("latitude", "text", array(
            "label" => Mage::helper("storelisting")->__("Latitude"),
            "name" => "latitude",
            'readonly' => true,
        ));

        $fieldset->addField("longitude", "text", array(
            "label" => Mage::helper("storelisting")->__("Longitude"),
            "name" => "longitude",
            'readonly' => true,

        ));


        $fieldset->addField("postcode", "text", array(
            "label" => Mage::helper("storelisting")->__("Postal code"),
            "name" => "postcode",
        ));

        $fieldset->addField("city", "text", array(
            "label" => Mage::helper("storelisting")->__("City"),
            "name" => "city",

        ));
//        $fieldset->addField("time_open", "text", array(
//            "label" => Mage::helper("storelisting")->__("Time Open"),
//            "name" => "time_open"
//
//        ));
//        $fieldset->addField('img', 'file', array(
//            'label'     => Mage::helper('storelisting')->__('File label'),
//            'required'  => false,
//            'name'      => 'img',
//        ));
        $fieldset->addField('img', 'image', array(
            'label'     => Mage::helper('storelisting')->__('Image'),
            'required'  => false,
            'name'      => 'img',
            'after_element_html' => "<img style='width:150px;height:150px' src='".Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."/storelisting/".$data_form->getImg()."' >",
        ));
//        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
//        $fieldset->addField('start_date', 'date', array(
//            'name'   => 'start_date',
//            'label'  => Mage::helper('storelisting')->__('Start Date'),
//            'title'  => Mage::helper('storelisting')->__('Start Date'),
//            'image'  => $this->getSkinUrl('images/grid-cal.gif'),
//            'input_format' => $dateFormatIso,
//            'format'       => $dateFormatIso,
//            'time' => true
//        ));
        $fieldset->addField('time_open', 'time', array(
            'label'     => Mage::helper('storelisting')->__('Time Open'),
            'style'     => 'width:45px',
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'time_open',
            'value'  => '12,04,15',
        ));
        $fieldset->addField('time_close', 'time', array(
            'label'     => Mage::helper('storelisting')->__('Time Close'),
            'style'     => 'width:45px',
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'time_close',
        ));
        if (Mage::getSingleton("adminhtml/session")->getStorelocationData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getStorelocationData());
            Mage::getSingleton("adminhtml/session")->setStorelocationData(null);
        } elseif (Mage::registry("storelocation_data")) {
            $form->setValues(Mage::registry("storelocation_data")->getData());
        }
        return parent::_prepareForm();
    }
}
