<?php
/**
 * Project name 05.Development
 * Created by:
 * User: Hoang Van Cong
 * Company: Theappnow.com
 * Email: conghv@theappnow.com
 * Date: 2/28/2017
 * Time: 5:10 PM
 */


class Theappnow_Baseprice_Block_Adminhtml_Tierprice_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract

{



    public function render(Varien_Object $row)

    {

        $value =  $row->getData($this->getColumn()->getIndex());

        $item=Mage::getModel('catalog/category')->load((int)$value);

        return $item->getName();



    }



}

?>