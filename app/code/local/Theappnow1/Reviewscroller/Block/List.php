<?php
/**
 * 
 */
class Theappnow_Reviewscroller_Block_List extends Mage_Core_Block_Template
{
    public function getReviews()
    {
		$reviews = Mage::getModel('review/review')
		                ->getResourceCollection()
		                ->addStoreFilter(Mage::app()->getStore()->getId())
		                ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
		                ->setDateOrder()
		                ->addRateVotes()
		                ->setPageSize(10)
		                ->setCurPage(1);

		
		return $reviews;
    }
}