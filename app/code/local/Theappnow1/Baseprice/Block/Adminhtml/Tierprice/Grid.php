<?php

class Theappnow_Baseprice_Block_Adminhtml_Tierprice_Grid extends Mage_Adminhtml_Block_Widget_Grid

{

    public function __construct()

    {

        parent::__construct();

        $this->setId('tierpriceGrid');

        $this->setDefaultSort('level');

        $this->setDefaultDir('ASC');

        $this->setSaveParametersInSession(true);

    }


    protected function _prepareCollection()

    {

        $collection = Mage::getModel('baseprice/tierprice')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();

    }

    protected function _prepareColumns()

    {


        $this->addColumn('id', array(

            'header' => Mage::helper('baseprice')->__('ID'),

            'align' => 'right',

            'width' => '50px',

            'index' => 'id',

        ));

        $this->addColumn('level', array(

            'header' => Mage::helper('baseprice')->__('Level'),

            'align' => 'left',
            'type' => 'number',
            'index' => 'level',
            'type' => 'number',
            'filter' => false,
            'sortable' => false
//            'renderer'  =>   'Theappnow_Baseprice_Block_Adminhtml_Price_Renderer_Category'

        ));
        $this->addColumn('qty', array(

            'header' => Mage::helper('baseprice')->__('Qty'),
            'align' => 'left',
            'width' => '100px',

            'index' => 'qty',
            'filter' => false,
            'sortable' => false,
            'type' => 'number'

        ));
        $this->addColumn('price_deviation', array(

            'header' => Mage::helper('baseprice')->__('Price deviation'),

            'align' => 'left',

            'width' => '100px',

            'index' => 'price_deviation',

            'type' => 'number',
            'filter' => false,
            'sortable' => false

        ));

        /**
         * TODO: cần lấy id của row
         */
        $link= Mage::helper('adminhtml')->getUrl('adminhtml/baseprice_tierprice/edit/') .'id';
        $this->addColumn('action_edit', array(
            'header'   => $this->helper('catalog')->__('Action'),
            'width'    => 15,
            'sortable' => false,
            'filter'   => false,
            'type'     => 'action',
            'getter'    => 'getId',
            'index' => 'id',
            'actions'  => array(
                array(
                    'url'     => $link,
                    'caption' => $this->helper('baseprice')->__('Edit'),
                    'field' => 'id'
                ),
            )
        ));
        return parent::_prepareColumns();

    }


    public function getRowUrl($row)

    {

        return $this->getUrl('*/*/edit', array('id' => $row->getId()));

    }

}
?>