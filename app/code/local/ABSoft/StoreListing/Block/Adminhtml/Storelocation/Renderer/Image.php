
<?php
    class ABSoft_StoreListing_Block_Adminhtml_Storelocation_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract{
        public function render(Varien_Object $row)   {
            $html = '<img id="' . $this->getColumn()->getId() . '" src="'.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$row->getData($this->getColumn()->getIndex()) . '"';
            $html .= '/>';
            return $html;
        }
    }
    ?>