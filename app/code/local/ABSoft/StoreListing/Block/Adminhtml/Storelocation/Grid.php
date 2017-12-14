<?php

class ABSoft_StoreListing_Block_Adminhtml_Storelocation_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId("storelocationGrid");
        $this->setDefaultSort("store_location_id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel("storelisting/storelocation")->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn("store_location_id", array(
            "header" => Mage::helper("storelisting")->__("ID"),
            "align" => "right",
            "width" => "50px",
            "type" => "number",
            "index" => "store_location_id",
        ));
        $this->addColumn("address", array(
            "header" => Mage::helper("storelisting")->__("Address"),
            "index" => "address",
        ));

        $this->addColumn('store_id', array(
            'header' => Mage::helper('storelisting')->__('Store'),
            'index' => 'store_id',
            'type' => 'options',
            'options' => ABSoft_StoreListing_Block_Adminhtml_Storelocation_Grid::getStores(),
        ));

        $this->addColumn("latitude", array(
            "header" => Mage::helper("storelisting")->__("Latitude"),
            "index" => "latitude",
        ));
        $this->addColumn("longitude", array(
            "header" => Mage::helper("storelisting")->__("Longitude"),
            "index" => "longitude",
        ));

        $this->addColumn("postcode", array(
            "header" => Mage::helper("storelisting")->__("Postal code"),
            "index" => "postcode",
        ));
        $this->addColumn("city", array(
            "header" => Mage::helper("storelisting")->__("City"),
            "index" => "city",
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
        $this->setMassactionIdField('store_location_id');
        $this->getMassactionBlock()->setFormFieldName('store_location_ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('remove_storelocation', array(
            'label' => Mage::helper('storelisting')->__('Remove Storelocation'),
            'url' => $this->getUrl('*/adminhtml_storelocation/massRemove'),
            'confirm' => Mage::helper('storelisting')->__('Are you sure?')
        ));
        return $this;
    }

    static public function getStores()
    {
        $store_locations = array();
        $store_location = Mage::getModel('storelisting/storelocation')->getCollection();
        foreach ($store_location as $item) {
            $store_locations[$item->getStoreId()] = "";
        }
        foreach (Mage::app()->getWebsites() as $website) {
            foreach ($website->getGroups() as $group) {
                foreach ($group->getStores() as $store) {
                    if (!array_key_exists($store->getStoreId(), $store_locations))
                        $stores[$store->getStoreId()] = $store->getName();
                }
            }
        }
        return $stores;

    }

    static public function get()
    {
        $data_array = array();
        return ($data_array);
    }


}