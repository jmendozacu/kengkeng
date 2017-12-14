<?php
/**
 * Created by PhpStorm.
 * User: Truong Tuan Dat
 * Date: 9/9/2017
 * Time: 1:41 PM
 */
class Inchoo_Blog_Model_Resource_Post_Collection extends Mage_Eav_Model_Entity_Collection_Abstract
{

    protected function _construct()
    {
        $this->_init('inchoo_blog/post');
    }

}