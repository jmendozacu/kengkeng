<?php
class ABSoft_StoreListing_Block_List extends Mage_Core_Block_Template
{
    //nhận lat,lng truy vấn tới bảng store_listing tìm ra cửa hàng sắp xếp theo thứ tự xa dần
    public function getStoreNearMe(){
        $sort = Mage::registry('sort');
        if($sort=='distance') {

        }
        if($sort=='best') {

        }
        if($sort=='lowest') {
            $colection = $products = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('price');


            $colection->getSelect()->join(
                [
                    's' => $colection->getTable('core/store'),
                ], $colection->getConnection()->quoteInto(
                'main_table.rating_id=rova.rating_id'),
                array('rova.percent_approved'));

        }
        if($sort=='highest') {

        }
        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
        $sql        = "SELECT core_store.name as namestore, img,time_open,store_location.store_id, core_store.code as code_store ,address ,city,latitude,longitude, 
        ( 3959 * acos( cos( radians(".Mage::registry('lat').") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".Mage::registry('lng').") ) + sin( radians(".Mage::registry('lat').") ) *sin( radians( latitude ) ) ) ) AS distance 
        FROM store_location INNER JOIN core_store ON core_store.store_id = store_location.store_id ORDER BY distance";
        $rows       = $connection->fetchAll($sql);
        return $rows;
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
        $summarys = Mage::getModel('review/review_summary')->getCollection()->addStoreFilter($store_id);
        if(count($summarys)>0) {
            $countSummary=0;
            foreach ($summarys->getItems() as $summary){
                $countSummary+=(int)$summary->getRatingSummary();
            }
            return $countSummary/count($summary->getReviewsCount());
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
    public function getLowestPriceProductStore($store_id){
        $colections = Mage::getModel('catalog/product')->getCollection()->addStoreFilter($store_id)->setOrder('price', 'ASC') //sets the order by price
        ->getFirstItem();
        return $colections->getPrice();
    }

    //đếm số lượng review của store
    public function getCountReview($store_id){
        return count($this->getAllReviewStore($store_id));
    }

}