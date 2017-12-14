<?php
class ABSoft_StoreListing_Block_Ajax extends Mage_Core_Block_Template
{
    //lấy tất reviews của store
    public function getReviews(){
        $store_id = Mage::registry('store_id');
        $colections = Mage::getModel('review/review')
            ->getCollection()
            ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
            ->addStoreFilter($store_id)
            ->addCustomRateVotes();
        $reviews = [];
        //lấy thông tin của store
        $reviews['infomation_store']=$this->getInfomationStore($store_id);
        foreach ($colections as $index => $colection) {
            $reviews['infomation_review'][$index] = $colection->getData();
            $percent = 0;
            foreach ($colection->getCustomRatingVotes() as $item) {
                $percent += $item->getPercent();
            }
            $reviews['infomation_review'][$index]['percent'] = $percent / count($colection->getCustomRatingVotes());
        }
        return $reviews;
    }
    //lấy thông tin của store
    public function getInfomationStore($store_id){
        //lấy tên của store
        $storeColection = Mage::getModel('core/store')->getCollection()->addFieldToFilter('store_id', $store_id)->getFirstItem();
        //lấy các tiêu chí đánh giá
        $colection = Mage::getModel('rating/rating')->getCollection()->addFieldToFilter('store_id', $store_id);
        $colection->getSelect()->join(
            [
                'rova' => $colection->getTable('rating/rating_vote_aggregated'),
            ], $colection->getConnection()->quoteInto('main_table.rating_id=rova.rating_id'),
            array('rova.percent_approved'));
        $data=[];
        foreach ($colection as $item) {
            $data['rating'][$item->getRatingCode()]=$item->getPercentApproved();
        }
        $data['name_store']=$storeColection->getName();
        $data['percent']=$this->getRatingSummaryStore($store_id);
        return $data;
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
}