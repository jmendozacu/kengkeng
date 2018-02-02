<?php

class ABSoft_StoreListing_IndexController extends Mage_Core_Controller_Front_Action
{
    public function IndexAction()
    {

        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
        $sql = "SELECT store_id,address ,city,latitude,longitude, ( 3959 * acos( cos( radians('-20.9408286') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('106.289409') ) + sin( radians('20.9408286') ) *sin( radians( latitude ) ) ) ) AS distance  FROM store_location ORDER BY distance ";
        $rows = $connection->fetchAll($sql);
        //var_dump($rows);
        //die;
        $this->loadLayout();
        $this->getLayout()->getBlock("head")->setTitle($this->__("Store list"));
        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
        $breadcrumbs->addCrumb("home", array(
            "label" => $this->__("Home Page"),
            "title" => $this->__("Home Page"),
            "link" => Mage::getBaseUrl()
        ));

        $breadcrumbs->addCrumb("store list", array(
            "label" => $this->__("Store list"),
            "title" => $this->__("Store list")
        ));

        $this->renderLayout();

    }

    public function listAction()
    {

        //kiểm tra request
        if ($this->getRequest()->getParam('search')) {
            //lấy thông lng,lat từ request
            $lng = $this->getRequest()->getParam('lng');
            $lat = $this->getRequest()->getParam('lat');
            $sort = $this->getRequest()->getParam('sort');
            $this->loadLayout();
            //gửi thông tin tới block
            Mage::register("lng", $lng);
            Mage::register("lat", $lat);
            Mage::register("sort", $sort);
            $isAjax = Mage::app()->getRequest()->isAjax();
            if($isAjax){

            } else {
                $this->getLayout()->getBlock("head")->setTitle($this->__("Store list"));
                $this->renderLayout();
            }

        }
        //nếu không đúng thông tin sẽ gửi lỗi 404
        else {
            Mage::app()->getFrontController()->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
            Mage::app()->getFrontController()->getResponse()->setHeader('Status', '404 File not found');

            $request = Mage::app()->getRequest();
            $request->initForward()
                ->setControllerName('indexController')
                ->setModuleName('Mage_Cms')
                ->setActionName('defaultNoRoute')
                ->setDispatched(false);
        }


    }

    public function testAction()
    { $colections = Mage::getModel('catalog/product')
        ->getCollection()
        ->addStoreFilter(7)
        ->addAttributeToSelect('price')
        ->addAttributeToSort('price', 'ASC')
        ;
        foreach ($colections as $product) {
            var_dump($product->getPrice());
        }
//    var_dump($colections);
    }
    public function getStar($store_id){
        $summarys = Mage::getModel('review/review_summary')->getCollection()->addStoreFilter($store_id);
        if(count($summarys)>0) {
            $countSummary=0;
            foreach ($summarys->getItems() as $summary){
                var_dump($summary->getRatingSummary());
                $countSummary+=(int)$summary->getRatingSummary();
            }
            return $countSummary/count($summary->getReviewsCount());
        } else {
            return 0;
        }
    }
    public function apiAction(){
        $arr = [1,2,3,4,5,6,7,8,9];
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($arr));
    }

    public function test1Action()
    {
        $helper = Mage::helper('catalog/product_configuration');
            $cartItems = Mage::getModel('checkout/cart')->getQuote()->getAllItems();
            foreach ($cartItems as $cartItem) {
                var_dump($helper->getCustomOptions($cartItem));

            }
    }

    public function getRatingSummaryStoreAction($store_id)
    {
        $summarys = Mage::getModel('review/review_summary')->getCollection()->addStoreFilter($store_id);
        $countSummary = 0;
        foreach ($summarys->getItems() as $summary) {
            $countSummary += (int)$summary->getRatingSummary();
        }
        return $countSummary;
    }

    public function getRatingSummaryStore($store_id)
    {
        $summarys = Mage::getModel('review/review_summary')->getCollection()->addStoreFilter($store_id);
        $countSummary = 0;
        foreach ($summarys->getItems() as $summary) {
            $countSummary += (int)$summary->getRatingSummary();
        }
        return $countSummary ;
    }

    public function getRatings()
    {
        $reviews = Mage::getModel('review/review')->getResourceCollection()->addStoreFilter(1)
            ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)->setDateOrder()
            ->addRateVotes();
        return $reviews;
    }

    public function getReviewOfStoreAction()
    {
        //return all review of store
        //listen request from custom.js
        if (Mage::app()->getRequest()->getPost()) {
            $store_id = Mage::app()->getRequest()->getPost("store_id");
            $colections = Mage::getModel('review/review')->getCollection()->addStoreFilter($store_id)->addCustomRateVotes();

            $reviews = [];
            $reviews['infomation_store']=$this->getInfomationStore($store_id);
            $reviews['infomation_store']['percent']=$this->getRatingSummaryStore($store_id);
            foreach ($colections as $index => $colection) {
                $reviews['infomation_review'][$index] = $colection->getData();
                $percent = 0;
                foreach ($colection->getCustomRatingVotes() as $item) {
                    $percent += $item->getPercent();
                }
                $reviews['infomation_review'][$index]['percent'] = $percent / count($colection->getCustomRatingVotes());
            }
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($reviews));
        }
    }

    public function getInfomationStore($store_id){
        /**
         * return Infomation store
         * var name_store, rating_code and rating's percent_approved
         */

        //get name_store
        $storeColection = Mage::getModel('core/store')->getCollection()->addFieldToFilter('store_id', $store_id)->getFirstItem();
        //get all store's rating and percent_approved
        $colection = Mage::getModel('rating/rating')->getCollection()->addFieldToFilter('store_id', $store_id);
        $colection->getSelect()->join(
            [
                'rova' => $colection->getTable('rating/rating_vote_aggregated'),
            ], $colection->getConnection()->quoteInto(
            'main_table.rating_id=rova.rating_id'),
            array('rova.percent_approved'));
        $data=[];
        foreach ($colection as $item) {
            $data['rating'][$item->getRatingCode()]=$item->getPercentApproved();
        }
        $data['name_store']=$storeColection->getName();
        return $data;
    }

    public function ajaxAction(){
        //kiểm tra request
        $isAjax = Mage::app()->getRequest()->isAjax();
        if($isAjax){
            $store_id = Mage::app()->getRequest()->getPost("store_id");
            //gửi store id tới block
            Mage::register('store_id',$store_id);
            //khởi tạo layout
            $layout = $this->getLayout();
            $update = $layout->getUpdate();
            $update->load('storelisting_index_ajax');
            $layout->generateXml();
            //khởi tạo template
            $layout->generateBlocks();
            $output = $layout->getOutput();
            //trả dữ liệu về cho ajax
            $this->getResponse()->setHeader('Content-type', 'application/json');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('outputHtml' => $output)));
        }
    }

    public function productAction(){
        $store_id = Mage::app()->getRequest()->getParam('store_id');
        Mage::register('store_id',$store_id);

        $this->loadLayout();
        $this->renderLayout();
    }
    public function productcategoryAction(){
        if(Mage::app()->getRequest()->isAjax()){
            $category_id = Mage::app()->getRequest()->getParam('category_id');
            Mage::register('category_id',$category_id);
            //khởi tạo layout
            $layout = $this->getLayout();
            $update = $layout->getUpdate();
            $update->load('storelisting_index_productcategory');
            $layout->generateXml();
            //khởi tạo template
            $layout->generateBlocks();
            $output = $layout->getOutput();
            //trả dữ liệu về cho ajax
            $this->getResponse()->setHeader('Content-type', 'application/json');
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('result' => $output)));
        }
    }
    public function renderAddUrlAction(){
        if(Mage::app()->getRequest()->isAjax()) {
            $product_sku = Mage::app()->getRequest()->getParam('sku');
            $product = Mage::getModel('catalog/category')->getCollection()->addAttributeToFilter('sku',$product_sku)->getFirstItem();
            $url = $helperCart = $this->helper('checkout/cart')->getAddUrl($product);

        }
    }

}