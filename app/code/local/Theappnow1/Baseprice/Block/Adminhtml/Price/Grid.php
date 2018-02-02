<?php

class Theappnow_Baseprice_Block_Adminhtml_Price_Grid extends Mage_Adminhtml_Block_Widget_Grid

{

    public function __construct()

    {

        parent::__construct();

        $this->setId('priceGrid');

        $this->setDefaultSort('baseprice_id');

        $this->setDefaultDir('ASC');

        $this->setSaveParametersInSession(true);

    }


    protected function _prepareCollection()

    {

        $collection = Mage::getModel('baseprice/price')->getCollection();

        $this->setCollection($collection);

        return parent::_prepareCollection();

    }

    protected function _prepareColumns()

    {


        $this->addColumn('baseprice_id', array(

            'header' => Mage::helper('baseprice')->__('ID'),

            'align' => 'right',

            'width' => '50px',

            'index' => 'baseprice_id',

        ));

        $this->addColumn('category_id', array(

            'header' => Mage::helper('baseprice')->__('Category Name'),

            'align' => 'left',

            'index' => 'category_id',
            'renderer' => 'Theappnow_Baseprice_Block_Adminhtml_Price_Renderer_Category'

        ));

        $this->addColumn('price_value', array(

            'header' => Mage::helper('baseprice')->__('Base Price'),

            'align' => 'left',

            'width' => '100px',

            'index' => 'price_value',

            'type' => 'number'

        ));


        return parent::_prepareColumns();

    }


    public function getRowUrl($row)

    {

        return $this->getUrl('*/*/edit', array('id' => $row->getId()));

    }

}

?>