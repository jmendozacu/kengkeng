<?php   
class ABSoft_StoreListing_Block_Index extends Mage_Core_Block_Template{
    /**
     * Html page block
     *
     * @category   ABSoft
     * @package    ABSoft_Storelisting
     * @author      Magento Core Team <core@magentocommerce.com>
     */

    public function __construct() {
        parent::__construct();
        $collection = $this->getStaffCollection();
        $this->setCollection($collection);
    }
    public function getStoreNearMe(){
        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
        $sql        = "SELECT core_store.name ,store_location.store_id,address ,img,city,latitude,longitude, ( 3959 * acos( cos( radians('-20.9408286') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('106.289409') ) + sin( radians('20.9408286') ) *sin( radians( latitude ) ) ) ) AS distance  FROM store_location INNER JOIN core_store ON core_store.store_id = store_location.store_id ORDER BY distance ";
        $rows       = $connection->fetchAll($sql);
        return $rows;
    }
    public function getActionOfForm(){
        return $this->getUrl('storelisting/index/list');
    }
    public function getAllCity(){
        $collection = Mage::getModel('storelisting/storelocation')->getCollection();
        $collection->getSelect()->group('city');
        return $collection;
    }


}