<?php
class ABSoft_StoreListing_Block_List extends Mage_Core_Block_Template
{
    //nhận lat,lng truy vấn tới bảng store_listing tìm ra cửa hàng sắp xếp theo thứ tự xa dần
    public function getStoreNearMe(){
        $sort = Mage::registry('sort');




        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
        $sql        = "SELECT core_store.name as namestore, img,time_open,store_location.store_id, core_store.code as code_store ,address ,city,latitude,longitude, 
        ( 3959 * acos( cos( radians(".Mage::registry('lat').") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".Mage::registry('lng').") ) + sin( radians(".Mage::registry('lat').") ) *sin( radians( latitude ) ) ) ) AS distance 
        FROM store_location INNER JOIN core_store ON core_store.store_id = store_location.store_id ORDER BY distance";


        $rows       = $connection->fetchAll($sql);
        $new_rows = array();
        $stores = Mage::getModel('storelisting/storelocation')->getCollection();

        foreach ($rows as $row) {
            $row['rating']= $this->getRatingSummaryStore($row['store_id']);
            $row['lowest']= $this->getLowestPriceProductStore($row['store_id']);
            $row['highest']= $this->getProductHighestPriceByStore($row['store_id']);
            array_push($new_rows,$row);
        }
        if($sort=='rating') {
            $new_rows = $this->getCustomSort($new_rows,'rating',"DESC");
        }
        if($sort=='lowest') {
            $new_rows = $this->getCustomSort($new_rows,'lowest');
        }
        if($sort=='highest') {
            $new_rows = $this->getCustomSort($new_rows,'highest',"DESC");
        }
//        var_dump($new_rows);
        return $new_rows;
    }
    public function getCustomSort($array,$index,$type="ASC"||"DESC") {
        $a = array();
        $b = array();

        foreach ($array as $key => $value) {
            $a[$key] = $value[$index];
        }
        if($type=="ASC") {
            asort($a);

        } else {
            arsort($a);
        }
        foreach ($a as $key => $value) {

            $b[] = $array[$key];
        }
        return $b ;
    }
    public function getStoreByCode(){
        $code = Mage::app()->getStore()->getCode();
        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
        $sql        = "SELECT core_store.name as namestore, img,time_open,store_location.store_id, core_store.code as code_store ,address ,city,latitude,longitude FROM store_location INNER JOIN core_store ON core_store.store_id = store_location.store_id WHERE core_store.code = '{$code}'";
        $rows       = $connection->fetchAll($sql);
        return $rows;
    }

    //lấy % star
    public function getRatingSummaryStore($store_id){
        $summarys = Mage::getModel('review/review_summary')
            ->getCollection()
            ->addStoreFilter($store_id)
        ->addFieldToFilter('reviews_count',array('gt'=>0));
        if(count($summarys)>0) {
            $countSummary=0;
            foreach ($summarys->getItems() as $summary){
                $countSummary+=(int)$summary->getRatingSummary();
            }
            return $countSummary/count($summarys->getItems());
        } else {
            return 0;
        }
    }

    //lấy tất cả review của store
    public function getAllReviewStore($store_id){
        $reviews = Mage::getModel('review/review')->getCollection()->addStoreFilter($store_id)
            ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
            ->setDateOrder()->addRateVotes();
        return $reviews;
    }

    //Tìm giá của sản phẩm thấp nhất

    /**
     * get product lowset price
     *
     * @param string $store_id
     * @return int $price
     */
    public function getLowestPriceProductStore($store_id){
        $colections = Mage::getModel('catalog/product')->getCollection()
            ->addStoreFilter($store_id)->addAttributeToSelect('price'); //sets the order by price
//        var_dump($colections->getSelectSql(true));
        $product_price=array();
        foreach ($colections as $product) {
            array_push($product_price,$product->getPrice());
        }
        sort($product_price);
//        var_dump($product_price);
        if(count($product_price)>0)
            return (float)$product_price[0];
        return 0;

    }
    /**
     * return product highest price in store
     * @param $store_id
     * @return int $highest_price
     */
    public function getProductHighestPriceByStore($store_id){
        $colections = Mage::getModel('catalog/product')->getCollection()
            ->addStoreFilter($store_id)->addAttributeToSelect('price'); //sets the order by price
        $product_price=array();
        foreach ($colections as $product) {
            array_push($product_price,$product->getPrice());
        }
        rsort($product_price);
        if(count($product_price)>0)
            return (float)$product_price[0];
        return 0;
    }
    //đếm số lượng review của store
    public function getCountReview($store_id){
        return count($this->getAllReviewStore($store_id));
    }

}