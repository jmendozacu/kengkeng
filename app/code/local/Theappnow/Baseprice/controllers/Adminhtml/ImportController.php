<?php
class PromotionalImportLoger
{
    /**
     * logging methos
     *
     * @param string $data
     *            : log content
     * @param string $type
     *            : log type
     */
    public function log($data, $type)
    {
        Mage::log("$type: $data");
    }
}

class Theappnow_Baseprice_Adminhtml_ImportController extends Mage_Adminhtml_Controller_Action
{
    public $attributes = [
        // 'entity_id',
        'sku',
        'name',
        'description',
        'short_description',
        'status',
        'image',
        'visibility',
        'meta_title',
        'meta_description',
        'meta_keyword',
        'price',
        'tax',
        'qty',
        'size',
        'color',
        'width',
        'height',
        'depth',
        'weight',
        'categories',
        'is_in_stock'
    ];

    public $canCreateConfigurableProduct = false;
    public $createTierPrice = false;

    public function productAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function getdatafileAction()
    {
        $allowedExts = array("csv");
        $temp = explode(".", $_FILES["productfeed"]["name"]);
        $extension = end($temp);
        $k = 1;
        $result = array();
        $maps = array();
        if (in_array($extension, $allowedExts)) {

            if (($handle = fopen($_FILES["productfeed"]["tmp_name"], "r")) !== FALSE) {
                while (($row = fgetcsv($handle, 10000, ";")) !== FALSE) {
                    if ($k == 1) {
                        $maps['maps'] = $row;
                    }
                    $k++;
                }
            }
            $mapping = '<table class="table-mapping" id="table-mapping">';
            $mapping .= '<tr>';
            $mapping .= '<th>Fields in csv file<br/>(first row)</th>';
            $mapping .= '<th>Product\'s Attribute</th>';
            $mapping .= '<tr>';
            $skuIndexColumn = null;
            foreach ($maps['maps'] as $k => $v) {
                $mapping .= '<tr class="row-key">';
                $mapping .= '<td class="csv-key">' . $v . '</td>';
                $mapping .= '<td class="data-key">' . $this->getSelectFields($k, $v) . '</td>';
                $mapping .= '</tr>';
                if($v=='sku') $skuIndexColumn = $k;
            }
            $mapping .= '</table>';
            $mapping.= sprintf('<input name="skuColumnIdx" value="%s" type="hidden" />', $skuIndexColumn);
            $result['mapFields'] = $mapping;
            echo json_encode($result);
        } else {
            echo '0';
        }

    }

    /**
     * TODO: auto assign csv header to product item
     */
    public function updateProductAction()
    {
        $magmiFolder = Mage::getStoreConfig('baseprice/general/magmi');   

        require_once(Mage::getBaseDir() ."/magmi/inc/magmi_defs.php");
        require_once(Mage::getBaseDIr()."/magmi/integration/inc/magmi_datapump.php");

        $startTime = microtime(true);

        if ($post = $this->getRequest()->getPost()) {

            $type = $post['importType']; //TODO: pass to import session
            if(isset($post['createConfigurable'])) $this->canCreateConfigurableProduct = true;
            if(isset($post['createTierprice'])) $this->createTierPrice= true;

            $dp = Magmi_DataPumpFactory::getDataPumpInstance("productimport");
            $dp->beginImportSession("default", $type, new PromotionalImportLoger());
//            $attributeSetId = 4;

            $typeId = Mage_Catalog_Model_Product_Type::TYPE_SIMPLE;
//            foreach ($post['mapFields'] as $k => $code) {
//                if ($code == 'trophy_size') {
//                    $typeId = Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE;
//                    break;
//                }
//            }

//            $attr = array('printing_type', 'trophy_size');
            // ob_start();
            $j = 1;
            $rows = array();
            if (($handle = fopen($_FILES["productfeed"]["tmp_name"], "r")) !== FALSE) {
                while (($row = fgetcsv($handle, 10000, ";")) !== FALSE) {
                    var_dump($row);
//                    if ($j > 1) {
                        $rows[] = $row;
                        // ob_end_flush();
                        // ob_flush();
//                    }
                    $j++;
                }
            }
           $field['sku']=0;
           $field['name']=0;
           $field['store']=0;
           $field['attribute_set']=0;
           $field['type']=0;
           $field['short_description']=0;
           $field['description']=0;
           $field['status ']=0;
           $field['is_in_stock']=0;
           $field['manage_stock']=0;
           $field['price']=0;
           $field['qty']=0;
           $field['categories']=0;
           $field['featured']=0;
           $field['weight']=0;
           $field['visibility']=0;
           $field['image']=0;
           $field['thumbnail']=0;
           $field['small_image']=0;
           $field['media_gallery']=0;
           $field['visibility']=0;
           $field['status']=0;
           $field['tax']=0;
            foreach ($rows as $k => $v) {

            }
            die;
            // ob_flush();
            if (count($rows)) {
                echo ucfirst($type) . ' product <br/>';
                $n = 1;
                $configurableAttribute = 'color,size';
                $simpleSkus = [];
                $previousSku = ''; //store previous sku, to compare and detect when created configurable product
                $sizes = [];
                $colors = [];
                $product = [];
				//$images = '';
                $isSimpleProduct = true;
                $skuColumnIdx = (int)$post['skuColumnIdx'];
                // var_dump($skuColumnIdx);
                foreach ($rows as $row) {
                    var_dump($row);
die;
//                    $qty = 0;
//
//                    $image = '';
//                    $isSimpleProduct = true;
                    $sku = $row[$skuColumnIdx];

                    // var_dump($sku);
                    // var_dump($row[$skuColumnIdx +1]);die;
                    //detect change sku, create configurable product
                    //&$dp, &$product, $previousSku, $sku, &$sizes, &$colors, &$simpleSku
//                    $this->createConfigurableProduct($dp, $product, $previousSku, $sku, $sizes, $colors, $simpleSkus);

                    $previousSku = $sku;

                    // $simpleSkus [] = $sku;

                    unset($product);
                    $product['sku'] = $sku;
                    $product['websites']= 'base';
                    $product['visibility']= Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE;
                    $product['attribute_set'] = 'Default';
                    $product['type'] = 'simple';
                    $product['product_type_id'] = 'simple';
                    $product['configurable_attributes'] = '';
                    $product['has_options'] = 1;
                    $product['weight']= 0.1;
                    $product['tax_class_id']= 0;
                    $product['store_id'] = 0;
                    // $product['simples_sku'] = '';
                    $product['Do you want to add print or embroidery service?:drop_down:0:1'] = '1x Embroidery logo:fixed:5|2x Embroidery logo:fixed:9|1x Printing Logo:fixed:4|2x Printing Logo:fixed:7';
                    $product['Is this a new setup or is a repeat artwork job?:drop_down:0:2'] = 'New logo artwork setup:fixed:0|Repeat artwork job:fixed:0';
                    $product['Where do you want it printed?:radio:0:3'] = 'Left chest:fixed:0|Left Sleeve:fixed:0|Middle of chest:fixed:0|Other:fixed:0|Right chest:fixed:0|Right Sleeve:fixed:0|Upper back:fixed:0';
                    //$product['0:area:5:4'] = 'Message:fixed:0';
                    $categoryName = '';

                    foreach ($post['mapFields'] as $k => $code) {
                        if($code == 'categories'){
                            if(count($categories = explode(';;', trim($row[$k])))){
                                if(count($categoryNames = explode('/', $categories[0]))){
                                    $categoryName = $categoryNames[0];
                                    break;
                                }else{
                                    $categoryName = $categories[0];
                                    break;
                                }
                            }else{
                                $categoryName = explode('/', trim($row[$k]))[0];
                                break;
                            }
                        }
                    }
                    
                    foreach ($post['mapFields'] as $k => $code) {
                        // echo $code;
                        switch ($code){
//                            case 'sku':
//                                $product['sku'] = trim($row[$k]);
//                                break;
                            case 'name':
                                $product['name'] = trim($row[$k]);
                                $product['url-key']= Mage::getModel('catalog/product_url')->formatUrlKey(trim($row[$k]));
                                break;
                            case 'description':
                                $product['short_description'] = $row[$k];
                                $product['description'] = $row[$k];
                                break;
                            case 'categories':
                                $product['categories'] = trim($row[$k]);                           
                                break;
                            case 'price':
                                $this->processProductPrice($product, trim($row[$k]), $categoryName);
                                break;
                            case 'status':
                                $status = 2;
                                if (trim($row[$k]) == 'publish' || trim($row[$k]) == 1) {
                                    $status = 1;
                                }
                                $product['status'] = $status;
                                break;
                            case 'is_in_stock':
                                $product['is_in_stock'] = trim($row[$k]);
                                break;
                            case 'visibility':
                                $product['visibility'] = trim($row[$k]);
                                break;              
                            case 'size':
                                $size = trim($row[$k]);
                                if(!in_array($size, $sizes)) $sizes[] = $size; //add unique size
                                $product['size'] = $size;
                                break;
                            case 'color':
                                $color = trim($row[$k]);
                                if(!in_array($color, $colors)) $colors[] = $color; //add unique color
                                $product['color'] = $color;
                                break;
                            case 'qty':
                                $product['qty'] = trim($row[$k]);
                                break;
							//case 'image':
								//$images = trim($row[$k]);
								//break;
                        }

                        
                    }
					$n++;
                    // var_dump($product);die;

                    $dp->ingest($product);
                }
                die;
                //create last configurable SKU
                $sku = '';//mark is last item
//                $this->createConfigurableProduct($dp, $product, $previousSku, $sku, $sizes, $colors);
				
				//$this->reindexAction();
                echo 'Total ' . ($n - 1) . ' products has been ' . $type . 'ed.<br/>';
                echo 'Excution time: ' . (microtime(true) - $startTime);

            }
            $dp->endImportSession();
        }
    }

    /**
     * Detect need create configurable product after list simple and end of file.
     * @param $product product row
     * @param $previousSku previous SKU to compare. SKU format: <simple sku>_<number>
     * @param $sku current row sku
     * @param $sizes unique size for configurable product
     * @param $simpleSku simple sku list
     * @param $colors list color
     */
    public function createConfigurableProduct(&$dp, &$product, &$previousSku, $sku, &$sizes, &$colors){

        if(!$this->canCreateConfigurableProduct) return; //get flag value

        if($previousSku == '' ) return; // skip first row

        $realPreviousSku = substr($previousSku,0, strrpos($previousSku,'_'));
        if($sku=='') //last item
            $realNewSku = '';
        else
            $realNewSku = substr($sku,0, strrpos($sku,'_'));

        // var_dump($realPreviousSku, $realNewSku, $sku);
        if($realNewSku != $realPreviousSku){
            // print_r('Create new configurable');
            // $product['simples_sku'] = join(',',$simpleSku);
            $product['size'] = join(',', $sizes);
            $product['color'] = join(',', $colors);
            $product['type'] = 'configurable';
            $product['configurable_attributes'] = 'color,size';
            $product['sku'] = $realPreviousSku;
            $product['visibility'] = Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH;
              // var_dump($product['simples_sku'], $product['sku']);

            $dp->ingest($product);
			//$product_id = Mage::getModel("catalog/product")->getIdBySku($product['sku']);
			//$this->processImage($product_id, $images);
			
            unset($previousSku);
            unset($sizes);
            // unset($simpleSku);
            unset($colors);
        }


    }
    protected function _getConnection($type = 'core_read')
    {
        return Mage::getSingleton('core/resource')->getConnection($type);
    }
    protected function _getTableName($tableName)
    {
        return Mage::getSingleton('core/resource')->getTableName($tableName);
    }

    protected function _updateStocks($productId, $qty)
    {
        $read = $this->_getConnection('core_read');
        $connection = $this->_getConnection('core_write');
        $stockId = null;
        $totalQty = $qty;
        $checkStatusSql = "
            SELECT COUNT(*)
            FROM {$this->_getTableName('cataloginventory_stock_status')}
            WHERE product_id = ? AND website_id = ?
        ";
        $website = 1;
        $newQty = $qty;
        $stockStatus = 1;

        $isInTable = $connection->fetchOne($checkStatusSql, array($productId, $website));
        if ($isInTable) {
            $sql = "
                    UPDATE {$this->_getTableName('cataloginventory_stock_status')}
                    SET qty = ?, stock_status = ?
                    WHERE product_id = ? AND website_id = ?
                ";
            $connection->query($sql, array($newQty, $stockStatus, $productId, $website));
        } else {
            if (is_null($stockId)) {
                $stockSql = "
                        SELECT stock_id FROM {$this->_getTableName('cataloginventory_stock_item')}
                        WHERE product_id = ?
                    ";
                $stockId = $connection->fetchOne($stockSql, array($productId));
                $stockId = $stockId > 0 ? $stockId : 1;
            }
            $sql = "
                    INSERT INTO {$this->_getTableName('cataloginventory_stock_status')}
                    (product_id, website_id, stock_id, qty, stock_status) VALUES (?,?,?,?,?)
                ";
            $connection->query($sql, array($productId, $website, $stockId, $newQty, $stockStatus));
        }
        $checkItemSql = "SELECT COUNT(*) FROM {$this->_getTableName('cataloginventory_stock_item')} WHERE product_id = ?";
        if ($read->fetchOne($checkItemSql, array($productId))) {
            $updateItemSql = "
                UPDATE {$this->_getTableName('cataloginventory_stock_item')}
                SET qty = ?, is_in_stock = ?
                WHERE product_id = ?
            ";
            $connection->query($updateItemSql, array($totalQty, 1, $productId));
        } else {
            $insertItemSql = "
                INSERT INTO {$this->_getTableName('cataloginventory_stock_item')}
                (product_id,stock_id,qty,use_config_min_qty,is_qty_decimal,use_config_backorders,use_config_min_sale_qty,use_config_max_sale_qty,is_in_stock,use_config_notify_stock_qty,use_config_manage_stock,use_config_qty_increments,use_config_enable_qty_inc)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)
            ";
            $connection->query($insertItemSql, array($productId, 1, $totalQty, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1));
        }
        return true;
    }

    protected function quickcreateAssociate($productId, $sizeLabel)
    {
        $website_ids = array(1);
        $printingValue = 232;
        $sizeValue = $this->getTrophyOptionValue('trophy_size', $sizeLabel);
        //var_dump($sizeValue);die;
        $product = Mage::getModel('catalog/product')->load($productId);
        $childIds = Mage::getModel('catalog/product_type_configurable')->getChildrenIds($productId);
        foreach ($childIds[0] as $val) {
            $data[$val] = array();
        }
        $dataSave = array(
            'visibility' => Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE,
            'status' => 1,
            'weight' => 0.1,
            'sku' => $product->getSku() . '-' . $printingValue . '-' . $sizeValue,
            'trophy_size' => $sizeValue,
            'printing_type' => $printingValue
        );
        $configproducts = Mage::getModel('catalog/product')->load($product->getId());
        $magentoProductModel = Mage::getModel('catalog/product');
        $magentoProductModel->setData($dataSave);
        $magentoProductModel->setName($configproducts->getName() . $sizeLabel . $printingValue);
        $magentoProductModel->setDescription($product->getDescription());
        $magentoProductModel->setShortDescription($product->getShortDescription());
        $magentoProductModel->setPrice($product->getPrice());

        $magentoProductModel->setCategoryIds($product->getCategoryIds());
        $magentoProductModel->setWebsiteIds($website_ids);
        $magentoProductModel->setTypeId('simple');
        $magentoProductModel->setAttributeSetId($configproducts->getAttributeSetId());
        $magentoProductModel->setTaxClassId("None");
        $magentoProductModel->setStockData(array(
                'is_in_stock' => 1,
                'qty' => 200
            )
        );
        $saved = $magentoProductModel->save();
        $lastid = $saved->getId();
        $data[$lastid] = array();
        $product->setConfigurableProductsData($data);
        $product->setCanSaveConfigurableAttributes(true);
        $product->save();
        $configattr = Mage::getModel('catalog/product_type_configurable')->getConfigurableAttributesAsArray($configproducts);

        $configproducts->setConfigurableAttributesData($configattr);
        $configproducts->save();

        return 0;
    }

    //get Trophy size
    protected function getTrophyOptionValue($attributeCode, $optionLabel)
    {
        $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $attributeCode);
        $options = $attribute->getSource()->getAllOptions(true, true);
        foreach ($options as $option) {
            if ($option['label'] == $optionLabel) {
                return $option['value'];
            }
        }
        return true;
    }

    //import attribute option: trophy size
    protected function importTrophySize($optionText)
    {
        $arrOptionText = explode('|', $optionText);
        $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', 'trophy_size');
        $options = $attribute->getSource()->getAllOptions(true, true);
        foreach ($arrOptionText as $newOption) {
            $flag = true;
            foreach ($options as $option) {
                if (trim($newOption) == $option['label']) {
                    $flag = false;
                    break;
                }
            }
            if ($flag) {
                $attribute->setData('option', array('value' => array('option' => array(trim($newOption)))));
                $attribute->save();
            }
        }
    }

    protected function getCategoryIds($arrCatMaping, $texmaping)
    {
        if (in_array(trim($texmaping), array_keys($arrCatMaping))) {
            $arrCatName = $arrCatMaping[trim($texmaping)];
        } else {
            $arrCatName = explode('>', trim($texmaping));
        }
        $result = array('2');
        foreach ($arrCatName as $name) {
            $category = Mage::getResourceModel('catalog/category_collection')
                ->addFieldToFilter('name', trim($name))
                ->getFirstItem();

            $result[] = $category->getId();

        }
        return $result;
    }

    protected function processImage($productId, $images)
    {
        $product = Mage::getModel('catalog/product')->setStoreId(0)->load($productId);
        $base = Mage::getBaseDir();
        $defaultImage = '';
        $dir = $base . DS . 'var' . DS . 'tmp' . DS;
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        $imgs = explode('|', $images);
        foreach ($imgs as $image) {

            try {
                if (strpos($image, 'http')) {
                    $rpos = strrpos($image, '/');
                    $file = substr($image, $rpos + 1);
                    if ($defaultImage == '') {
                        $defaultImage = $file;
                    }
                    if (!file_exists($dir . $file)) {
                        file_put_contents($dir . $file, file_get_contents($image));
                    }
                } else {
                    $dir = $base . DS . 'images' . DS;
                    if (!is_dir($dir)) mkdir($dir, 0777, true);
                    $file = str_replace(' ', '', $image);
                }
                $attributes = $product->getTypeInstance(true)->getSetAttributes($product);
                $mediaGalleryAttribute = $attributes['media_gallery'];
                /* @var $mediaGalleryAttribute Mage_Catalog_Model_Resource_Eav_Attribute */
                $pathImage = $mediaGalleryAttribute->getBackend()->addImage($product, $dir . $file, null, false, false);
                $realImage = str_replace(Mage::getBaseUrl('media') . 'catalog/product', '', $pathImage);
                $product->setImage($realImage)
                    ->setSmallImage($realImage)
                    ->setThumbnail($realImage);
                $product->save();
            } catch (Exception $e) {
            }
        }

    }

    public function reindexAction()
    {
        try {
            $processes = $this->_parseIndexerString('all');
            Mage::dispatchEvent('shell_reindex_init_process');
            foreach ($processes as $process) {
                /* @var $process Mage_Index_Model_Process */
                try {
                    $startTime = microtime(true);
                    $process->reindexEverything();
                    $resultTime = microtime(true) - $startTime;
                    Mage::dispatchEvent($process->getIndexerCode() . '_shell_reindex_after');
                    echo $process->getIndexer()->getName()
                        . " index was rebuilt successfully in " . gmdate('H:i:s', $resultTime) . "\n";
                } catch (Mage_Core_Exception $e) {
                    echo $e->getMessage() . "\n";
                } catch (Exception $e) {
                    echo $process->getIndexer()->getName() . " index process unknown error:\n";
                    echo $e . "\n";
                }
            }
            Mage::dispatchEvent('shell_reindex_finalize_process');
        } catch (Exception $e) {
            Mage::dispatchEvent('shell_reindex_finalize_process');
            echo $e->getMessage() . "\n";
        }
    }

    protected function _parseIndexerString($string)
    {
        $processes = array();
        if ($string == 'all') {
            $collection = $this->_getIndexer()->getProcessesCollection();
            foreach ($collection as $process) {
                if ($process->getIndexer()->isVisible() === false) {
                    continue;
                }
                $processes[] = $process;
            }
        } else if (!empty($string)) {
            $codes = explode(',', $string);
            $codes = array_map('trim', $codes);
            $processes = $this->_getIndexer()->getProcessesCollectionByCodes($codes);
            foreach ($processes as $key => $process) {
                if ($process->getIndexer()->getVisibility() === false) {
                    unset($processes[$key]);
                }
            }
            if ($this->_getIndexer()->hasErrors()) {
                echo implode(PHP_EOL, $this->_getIndexer()->getErrors()), PHP_EOL;
            }
        }
        return $processes;
    }
    protected function _getIndexer()
    {
        $factory = new Mage_Core_Model_Factory();
        return $factory->getSingleton($factory->getIndexClassAlias());
    }
    protected function getSelectFields($position, $colKey = '')
    {
        $skuColumnIdx = 0;
        $result = '<select name="mapFields[' . $position . ']">';
        $result .= '<option value="">- Select one Attribute __</option>';
        foreach ($this->attributes as $key){
            $result .=  sprintf("<option value='%s' %s>&nbsp;&nbsp;&nbsp;- %s</option>", $key, ($key==$colKey? 'selected' : ''), ucfirst($key));

        }


        $result .= '</select>';
        return $result;
    }
    protected function getProductBySku($row, $mapFields)
    {
        foreach ($mapFields as $k => $v) {
            if ($v == 'sku') {
                $id = Mage::getModel('catalog/product')->getIdBySku($row[$k]);
                if ($id) {
                    return Mage::getModel('catalog/product')->setData('entity_id', $id);
                }
                break;
            }
        }
        return false;
    }
    public function updatePrice($id, $price, $tierPrice)
    {
        $product = Mage::getModel('catalog/product')->load($id);
        $product->setPrice($price)
            ->setTierPrice($tierPrice);
        $product->save();
    }

    /**
     * Process product price, tier price
     * @param $product product row
     * @param $cost product cust import from csv
     */
    public function processProductPrice(&$product, $cost, $categoryName){
        //tax
        $defaultTax = (int)Mage::getStoreConfig('baseprice/general/tax');

        if (!$defaultTax) {
            $defaultTax = 100;
        }
        $defaultTax = $defaultTax / 100;

        $costIncludeGST = $cost * $defaultTax;

        //base price
        $collection = Mage::getModel('catalog/category')->getCollection()
                    ->addAttributeToSelect('name');
        foreach ($collection as $item) {
            if($item->getName() == $categoryName)
            {
                $categoryId = $item->getId();
                break;
            }
        }
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read'); 
        $writeConnection = $resource->getConnection('core_write');
        $queryPrice = 'SELECT price_value FROM ' . $resource->getTableName('baseprice/price') . ' WHERE category_id=' . $categoryId;
        $baseprice = $readConnection->fetchOne($queryPrice);

        //price
        $price = $cost + $costIncludeGST + $baseprice;
        $product['price']= number_format($price,2);

        //tier price
        if(!$this->createTierPrice) return; //skip create tierprice based on config data

        $queryTierPrice = 'SELECT price_deviation,qty FROM ' . $resource->getTableName('baseprice/tierprice');
        $deviations = $readConnection->fetchAll($queryTierPrice);

        $tierPrice = '';
        foreach ($deviations as $deviation) {
            $tierPrice.=$deviation['qty'].':'.number_format(($price-$deviation['price_deviation']),2).';';
        }
        $tierPrice = rtrim($tierPrice, ';');
        // var_dump($tierPrice);die;

        $product['tier_price:all groups'] = $tierPrice;

    }
}