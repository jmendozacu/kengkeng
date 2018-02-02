<?php

/**
 * Class Theappnow_Baseprice_Adminhtml_ExportController
 * TODO: Refactory this class
 *
 */
class Theappnow_Baseprice_Adminhtml_ExportController extends Mage_Adminhtml_Controller_Action{

    public function productAction(){
        if($data=$this->getRequest()->getPost()){
            $read = $this->_getConnection('core_read');
            $sqlAttributeSet = 'select * from eav_attribute_set';
            $datas = $read->fetchAll($sqlAttributeSet);
            $attributeSet = array();
            $hasOptions = array('0'=>'No','1'=>'Yes');
            foreach($datas as $item){
                $attributeSet[$item['attribute_set_id']] = $item['attribute_set_name'];
            }

            $name = 'product-'.time().'.csv';
            if (!is_dir(Mage::getBaseDir() . '/media/ducns_export/')) {
                mkdir(Mage::getBaseDir() . '/media/ducns_export/', 0755);
            }
            if (!is_dir(Mage::getBaseDir() . '/media/ducns_export/product/')) {
                mkdir(Mage::getBaseDir() . '/media/ducns_export/product/', 0755);
            }
            $fileName = Mage::getBaseDir() . '/media/ducns_export/product/'. $name;
            $file = fopen($fileName,"w");
            $dataTile = array('id','sku','name','description',
                'short description','url','categoryIds','meta_title','meta_description','image',
                'size','price','weight','status','visibility','meta_keyword');
            fputcsv($file,$dataTile,',');
            $sql = 'select * from catalog_product_entity where sku is not null and type_id="configurable"';
            $products = $read->fetchAll($sql);
            foreach($products as $item){
                $itemData = array();
                $itemData[0] = $item['entity_id'];
                $itemData[1] = $item['sku'];
                // product name
                $sql = 'SELECT value FROM catalog_product_entity_varchar WHERE attribute_id = 71 and store_id in(0,?) and entity_id=? order by store_id desc';
                $itemData[5] = $read->fetchOne($sql,array($data['store_id'],$item['entity_id']));

                //Description
                $sql = 'SELECT value FROM catalog_product_entity_text WHERE attribute_id = 72 and store_id in(0,?) and entity_id=? order by store_id desc';
                $itemData[6] = strip_tags($read->fetchOne($sql,array($data['store_id'],$item['entity_id'])));

                //short Description
                $sql = 'SELECT value FROM catalog_product_entity_text WHERE attribute_id = 73 and store_id in(0,?) and entity_id=? order by store_id desc';
                $itemData[7] = strip_tags($read->fetchOne($sql,array($data['store_id'],$item['entity_id'])));

                //url
                $sql = 'SELECT request_path FROM core_url_rewrite WHERE category_id is null and store_id in(0,?) and product_id=? order by store_id desc';
                $itemData[8] = $read->fetchOne($sql,array($data['store_id'],$item['entity_id']));
                //categoryIds
                $sql = 'SELECT GROUP_CONCAT(category_id) FROM catalog_category_product where product_id=?';
                $itemData[9] = $read->fetchOne($sql,array($item['entity_id']));

                //meta_title
                $sql = 'SELECT value FROM catalog_product_entity_varchar WHERE attribute_id = 82 and store_id in(0,?) and entity_id=? order by store_id desc';
                $itemData[10] = strip_tags($read->fetchOne($sql,array($data['store_id'],$item['entity_id'])));

                //meta_description
                $sql = 'SELECT value FROM catalog_product_entity_varchar WHERE attribute_id = 84 and store_id in(0,?) and entity_id=? order by store_id desc';
                $itemData[11] = strip_tags($read->fetchOne($sql,array($data['store_id'],$item['entity_id'])));
//image
                $sql = 'SELECT value FROM catalog_product_entity_varchar WHERE attribute_id = 85 and store_id in(0,?) and entity_id=? order by store_id desc';
                $itemData[12] = strip_tags($read->fetchOne($sql,array($data['store_id'],$item['entity_id'])));

                // Size
                $sql = 'SELECT value FROM catalog_product_entity_varchar WHERE attribute_id = 151 and store_id in(0,?) and entity_id=? order by store_id desc';
                $itemData[15] = strip_tags($read->fetchOne($sql,array($data['store_id'],$item['entity_id'])));
//price
                $sql = 'SELECT value FROM catalog_product_entity_decimal WHERE attribute_id = 75 and store_id in(0,?) and entity_id=? order by store_id desc';
                $itemData[16] = strip_tags($read->fetchOne($sql,array($data['store_id'],$item['entity_id'])));
//Weight
                $sql = 'SELECT value FROM catalog_product_entity_decimal WHERE attribute_id = 80 and store_id in(0,?) and entity_id=? order by store_id desc';
                $itemData[17] = strip_tags($read->fetchOne($sql,array($data['store_id'],$item['entity_id'])));
//status
                $sql = 'SELECT value FROM catalog_product_entity_int WHERE attribute_id = 96 and store_id in(0,?) and entity_id=? order by store_id desc';
                $itemData[18] = strip_tags($read->fetchOne($sql,array($data['store_id'],$item['entity_id'])));
//visibility
                $sql = 'SELECT value FROM catalog_product_entity_int WHERE attribute_id = 102 and store_id in(0,?) and entity_id=? order by store_id desc';
                $itemData[19] = strip_tags($read->fetchOne($sql,array($data['store_id'],$item['entity_id'])));
//meta_keyword
                $sql = 'SELECT value FROM catalog_product_entity_text WHERE attribute_id = 83 and store_id in(0,?) and entity_id=? order by store_id desc';
                $itemData[20] = strip_tags($read->fetchOne($sql,array($data['store_id'],$item['entity_id'])));

                fputcsv($file,$itemData,',');

            }
            Mage::register('product_file',str_replace('index.php/','',Mage::getBaseUrl().'media/ducns_export/product/'. $name));
        }
        $this->loadLayout();
        $this->_setActiveMenu('system');
        $this->renderLayout();
    }

    protected function _getConnection($type = 'core_read'){
        return Mage::getSingleton('core/resource')->getConnection($type);
    }
}